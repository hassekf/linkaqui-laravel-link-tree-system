<?php

namespace App\Livewire\Builder;

use App\Enums\LinkType;
use App\Models\Embed;
use App\Models\Link;
use Livewire\Attributes\Locked;
use Livewire\Component;

class LinkList extends Component
{
    public bool $showEditor = false;

    #[Locked]
    public ?int $editingLinkId = null;

    // Editor fields
    public string $title = '';

    public string $url = '';

    public string $icon = '';

    public string $bgColor = '';

    public string $textColor = '';

    public string $type = 'link';

    public function addLink(): void
    {
        $this->resetEditor();
        $this->showEditor = true;
    }

    public function addHeading(): void
    {
        $this->resetEditor();
        $this->type = 'heading';
        $this->showEditor = true;
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
        $this->type = $link->type->value;
        $this->showEditor = true;
    }

    public function saveLink(): void
    {
        $rules = [
            'title' => ['required', 'string', 'max:100'],
            'type' => ['required', 'string', 'in:link,heading,divider'],
        ];

        if ($this->type === 'link') {
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
            'type' => LinkType::from($this->type),
        ];

        if ($this->type === 'link') {
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

        $this->resetEditor();
        $this->showEditor = false;
        $this->dispatch('preview-refresh');
    }

    public function deleteLink(int $id): void
    {
        Link::where('user_id', auth()->id())->findOrFail($id)->delete();
        $this->dispatch('preview-refresh');
    }

    public function toggleLink(int $id): void
    {
        $link = Link::where('user_id', auth()->id())->findOrFail($id);
        $link->update(['is_active' => ! $link->is_active]);
        $this->dispatch('preview-refresh');
    }

    public function reorderLinks(int $id, int $position): void
    {
        Link::where('user_id', auth()->id())
            ->where('id', $id)
            ->update(['position' => $position]);

        $this->dispatch('preview-refresh');
    }

    private function resetEditor(): void
    {
        $this->editingLinkId = null;
        $this->title = '';
        $this->url = '';
        $this->icon = '';
        $this->bgColor = '';
        $this->textColor = '';
        $this->type = 'link';
    }

    public function render()
    {
        return view('livewire.builder.link-list', [
            'links' => Link::where('user_id', auth()->id())->ordered()->get(),
        ]);
    }
}
