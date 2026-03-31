<?php

namespace App\Livewire\Builder;

use App\Enums\EmbedType;
use App\Enums\LinkType;
use App\Models\Embed;
use App\Models\Link;
use App\Services\EmbedService;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Component;

class ContentList extends Component
{
    // Editor state
    public bool $showLinkEditor = false;

    public bool $showEmbedEditor = false;

    #[Locked]
    public ?int $editingLinkId = null;

    #[Locked]
    public ?int $editingEmbedId = null;

    // Link editor fields
    public string $title = '';

    public string $url = '';

    public string $icon = '';

    public string $bgColor = '';

    public string $textColor = '';

    public string $linkType = 'link';

    // Embed editor fields
    public string $rawUrl = '';

    public string $embedTitle = '';

    public ?string $detectedType = null;

    public ?string $embedUrl = null;

    /** @return Collection<int, object> */
    #[Computed]
    public function contentItems(): Collection
    {
        $links = Link::where('user_id', auth()->id())->orderBy('position')->get()
            ->map(fn ($link) => (object) [
                'sort_key' => 'link-'.$link->id,
                'type' => 'link',
                'item' => $link,
                'position' => $link->position,
            ]);

        $embeds = Embed::where('user_id', auth()->id())->orderBy('position')->get()
            ->map(fn ($embed) => (object) [
                'sort_key' => 'embed-'.$embed->id,
                'type' => 'embed',
                'item' => $embed,
                'position' => $embed->position,
            ]);

        return collect([...$links, ...$embeds])->sortBy('position')->values();
    }

    // — Link actions —

    public function addLink(): void
    {
        $this->resetLinkEditor();
        $this->showLinkEditor = true;
        $this->showEmbedEditor = false;
    }

    public function addHeading(): void
    {
        $this->resetLinkEditor();
        $this->linkType = 'heading';
        $this->showLinkEditor = true;
        $this->showEmbedEditor = false;
    }

    public function addDivider(): void
    {
        $maxPosition = max(
            Link::where('user_id', auth()->id())->max('position') ?? 0,
            Embed::where('user_id', auth()->id())->max('position') ?? 0,
        );

        Link::create([
            'user_id' => auth()->id(),
            'type' => LinkType::Divider,
            'title' => '',
            'position' => $maxPosition + 1,
        ]);

        $this->dispatch('preview-refresh');
    }

    public function editLink(int $id): void
    {
        $link = Link::where('user_id', auth()->id())->findOrFail($id);

        $this->editingLinkId = $link->id;
        $this->title = $link->title;
        $this->url = $link->url ?? '';
        $this->icon = $link->icon ?? '';
        $this->bgColor = $link->bg_color ?? '';
        $this->textColor = $link->text_color ?? '';
        $this->linkType = $link->type->value;
        $this->showLinkEditor = true;
        $this->showEmbedEditor = false;
    }

    public function saveLink(): void
    {
        $rules = [
            'title' => ['required', 'string', 'max:100'],
            'linkType' => ['required', 'string', 'in:link,heading,divider'],
        ];

        if ($this->linkType === 'link') {
            if ($this->url && ! preg_match('#^https?://#i', $this->url)) {
                $this->url = 'https://'.$this->url;
            }

            $rules['url'] = ['required', 'url', 'max:2048'];
            $rules['icon'] = ['nullable', 'string', 'max:50'];
            $rules['bgColor'] = ['nullable', 'string', 'max:30'];
            $rules['textColor'] = ['nullable', 'string', 'max:30'];
        }

        $this->validate($rules);

        $data = [
            'title' => $this->title,
            'type' => LinkType::from($this->linkType),
        ];

        if ($this->linkType === 'link') {
            $data['url'] = $this->url;
            $data['icon'] = $this->icon ?: null;
            $data['bg_color'] = $this->bgColor ?: null;
            $data['text_color'] = $this->textColor ?: null;
        }

        if ($this->editingLinkId) {
            $link = Link::where('user_id', auth()->id())->findOrFail($this->editingLinkId);
            $link->update($data);
        } else {
            $maxPosition = max(
                Link::where('user_id', auth()->id())->max('position') ?? 0,
                Embed::where('user_id', auth()->id())->max('position') ?? 0,
            );
            $data['user_id'] = auth()->id();
            $data['position'] = $maxPosition + 1;
            Link::create($data);
        }

        $this->closeLinkEditor();
        $this->dispatch('preview-refresh');
        $this->dispatch('toast', message: 'Link salvo!');
    }

    public function deleteLink(int $id): void
    {
        Link::where('user_id', auth()->id())->findOrFail($id)->delete();
        $this->dispatch('preview-refresh');
        $this->dispatch('toast', message: 'Item removido.');
    }

    public function toggleLink(int $id): void
    {
        $link = Link::where('user_id', auth()->id())->findOrFail($id);
        $link->update(['is_active' => ! $link->is_active]);
        $this->dispatch('preview-refresh');
        $this->dispatch('toast', message: $link->fresh()->is_active ? 'Item ativado.' : 'Item desativado.');
    }

    public function closeLinkEditor(): void
    {
        $this->resetLinkEditor();
        $this->showLinkEditor = false;
    }

    // — Embed actions —

    public function addEmbed(): void
    {
        $this->resetEmbedEditor();
        $this->showEmbedEditor = true;
        $this->showLinkEditor = false;
    }

    public function editEmbed(int $id): void
    {
        $embed = Embed::where('user_id', auth()->id())->findOrFail($id);
        $this->editingEmbedId = $embed->id;
        $this->rawUrl = $embed->embed_url;
        $this->embedTitle = $embed->title ?? '';
        $this->detectedType = $embed->type->value;
        $this->embedUrl = $embed->embed_url;
        $this->showEmbedEditor = true;
        $this->showLinkEditor = false;
    }

    public function updatedRawUrl(): void
    {
        $service = app(EmbedService::class);
        $this->embedUrl = $service->parse($this->rawUrl);
        $this->detectedType = $service->detectType($this->rawUrl)?->value;
    }

    public function saveEmbed(): void
    {
        if ($this->rawUrl && ! preg_match('#^https?://#i', $this->rawUrl)) {
            $this->rawUrl = 'https://'.$this->rawUrl;
            $this->updatedRawUrl();
        }

        $this->validate([
            'rawUrl' => ['required', 'url', 'max:2048'],
            'embedTitle' => ['nullable', 'string', 'max:100'],
        ]);

        if (! $this->embedUrl || ! $this->detectedType) {
            $this->addError('rawUrl', 'URL não reconhecida. Plataformas suportadas: YouTube, Spotify, SoundCloud, Twitch, Vimeo, TikTok, Apple Music.');

            return;
        }

        if ($this->editingEmbedId) {
            $embed = Embed::where('user_id', auth()->id())->findOrFail($this->editingEmbedId);
            $embed->update([
                'type' => EmbedType::from($this->detectedType),
                'embed_url' => $this->embedUrl,
                'title' => $this->embedTitle ?: null,
            ]);
        } else {
            $maxPosition = max(
                Link::where('user_id', auth()->id())->max('position') ?? 0,
                Embed::where('user_id', auth()->id())->max('position') ?? 0,
            );
            Embed::create([
                'user_id' => auth()->id(),
                'type' => EmbedType::from($this->detectedType),
                'embed_url' => $this->embedUrl,
                'title' => $this->embedTitle ?: null,
                'position' => $maxPosition + 1,
            ]);
        }

        $this->closeEmbedEditor();
        $this->dispatch('preview-refresh');
        $this->dispatch('toast', message: 'Embed salvo!');
    }

    public function deleteEmbed(int $id): void
    {
        Embed::where('user_id', auth()->id())->findOrFail($id)->delete();
        $this->dispatch('preview-refresh');
        $this->dispatch('toast', message: 'Embed removido.');
    }

    public function toggleEmbed(int $id): void
    {
        $embed = Embed::where('user_id', auth()->id())->findOrFail($id);
        $embed->update(['is_active' => ! $embed->is_active]);
        $this->dispatch('preview-refresh');
        $this->dispatch('toast', message: $embed->fresh()->is_active ? 'Item ativado.' : 'Item desativado.');
    }

    public function closeEmbedEditor(): void
    {
        $this->resetEmbedEditor();
        $this->showEmbedEditor = false;
    }

    // — Reordering —

    public function reorderContent(string $sortKey, int $newPosition): void
    {
        [$movedType, $movedId] = explode('-', $sortKey, 2);

        // Get all items in current order
        $items = $this->contentItems->toArray();

        // Remove the moved item from the list
        $movedItem = null;
        $remaining = [];
        foreach ($items as $item) {
            if ($item->sort_key === $sortKey) {
                $movedItem = $item;
            } else {
                $remaining[] = $item;
            }
        }

        if (! $movedItem) {
            return;
        }

        // Insert at new position (0-based)
        array_splice($remaining, $newPosition, 0, [$movedItem]);

        // Update all positions
        foreach ($remaining as $index => $item) {
            [$type, $id] = explode('-', $item->sort_key, 2);

            if ($type === 'link') {
                Link::where('user_id', auth()->id())
                    ->where('id', $id)
                    ->update(['position' => $index]);
            } elseif ($type === 'embed') {
                Embed::where('user_id', auth()->id())
                    ->where('id', $id)
                    ->update(['position' => $index]);
            }
        }

        unset($this->contentItems);
        $this->dispatch('preview-refresh');
    }

    // — Private helpers —

    private function resetLinkEditor(): void
    {
        $this->editingLinkId = null;
        $this->title = '';
        $this->url = '';
        $this->icon = '';
        $this->bgColor = '';
        $this->textColor = '';
        $this->linkType = 'link';
        $this->resetValidation();
    }

    private function resetEmbedEditor(): void
    {
        $this->editingEmbedId = null;
        $this->rawUrl = '';
        $this->embedTitle = '';
        $this->detectedType = null;
        $this->embedUrl = null;
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.builder.content-list');
    }
}
