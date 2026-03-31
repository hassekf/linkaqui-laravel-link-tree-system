<?php

namespace App\Livewire\Builder;

use App\Enums\EmbedType;
use App\Models\Embed;
use App\Models\Link;
use App\Services\EmbedService;
use Livewire\Attributes\Locked;
use Livewire\Component;

class EmbedManager extends Component
{
    public bool $showEditor = false;

    public string $rawUrl = '';

    public string $title = '';

    public ?string $detectedType = null;

    public ?string $embedUrl = null;

    #[Locked]
    public ?int $editingId = null;

    public function updatedRawUrl(): void
    {
        $service = app(EmbedService::class);
        $this->embedUrl = $service->parse($this->rawUrl);
        $this->detectedType = $service->detectType($this->rawUrl)?->value;
    }

    public function addEmbed(): void
    {
        $this->resetEditor();
        $this->showEditor = true;
    }

    public function editEmbed(int $id): void
    {
        $embed = Embed::where('user_id', auth()->id())->findOrFail($id);
        $this->editingId = $embed->id;
        $this->rawUrl = $embed->embed_url;
        $this->title = $embed->title ?? '';
        $this->detectedType = $embed->type->value;
        $this->embedUrl = $embed->embed_url;
        $this->showEditor = true;
    }

    public function saveEmbed(): void
    {
        if ($this->rawUrl && ! preg_match('#^https?://#i', $this->rawUrl)) {
            $this->rawUrl = 'https://'.$this->rawUrl;
            $this->updatedRawUrl();
        }

        $this->validate([
            'rawUrl' => ['required', 'url', 'max:2048'],
            'title' => ['nullable', 'string', 'max:100'],
        ]);

        if (! $this->embedUrl || ! $this->detectedType) {
            $this->addError('rawUrl', 'URL não reconhecida. Plataformas suportadas: YouTube, Spotify, SoundCloud, Twitch, Vimeo, TikTok, Apple Music.');

            return;
        }

        if ($this->editingId) {
            $embed = Embed::where('user_id', auth()->id())->findOrFail($this->editingId);
            $embed->update([
                'type' => EmbedType::from($this->detectedType),
                'embed_url' => $this->embedUrl,
                'title' => $this->title ?: null,
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
                'title' => $this->title ?: null,
                'position' => $maxPosition + 1,
            ]);
        }

        $this->resetEditor();
        $this->showEditor = false;
        $this->dispatch('preview-refresh');
    }

    public function deleteEmbed(int $id): void
    {
        Embed::where('user_id', auth()->id())->findOrFail($id)->delete();
        $this->dispatch('preview-refresh');
    }

    public function toggleEmbed(int $id): void
    {
        $embed = Embed::where('user_id', auth()->id())->findOrFail($id);
        $embed->update(['is_active' => ! $embed->is_active]);
        $this->dispatch('preview-refresh');
    }

    private function resetEditor(): void
    {
        $this->editingId = null;
        $this->rawUrl = '';
        $this->title = '';
        $this->detectedType = null;
        $this->embedUrl = null;
    }

    public function render()
    {
        return view('livewire.builder.embed-manager', [
            'embeds' => Embed::where('user_id', auth()->id())->ordered()->get(),
        ]);
    }
}
