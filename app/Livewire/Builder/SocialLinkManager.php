<?php

namespace App\Livewire\Builder;

use App\Enums\SocialPlatform;
use App\Models\SocialLink;
use Livewire\Attributes\Locked;
use Livewire\Component;

class SocialLinkManager extends Component
{
    public bool $showEditor = false;

    public string $platform = '';

    public string $input = '';

    #[Locked]
    public ?int $editingId = null;

    public function addSocial(string $platform): void
    {
        $existing = SocialLink::where('user_id', auth()->id())
            ->where('platform', $platform)
            ->first();

        if ($existing) {
            $this->editSocial($existing->id);

            return;
        }

        $this->resetEditor();
        $this->platform = $platform;
        $this->showEditor = true;
    }

    public function editSocial(int $id): void
    {
        $social = SocialLink::where('user_id', auth()->id())->findOrFail($id);
        $this->editingId = $social->id;
        $this->platform = $social->platform->value;

        $platformEnum = SocialPlatform::from($this->platform);
        $this->input = $platformEnum->extractUsername($social->url);
        $this->showEditor = true;
    }

    public function saveSocial(): void
    {
        $platformEnum = SocialPlatform::from($this->platform);

        $rules = [
            'platform' => ['required', 'string'],
            'input' => ['required', 'string', 'max:2048'],
        ];

        if (! $platformEnum->usesUsername()) {
            if ($platformEnum === SocialPlatform::Email) {
                $rules['input'] = ['required', 'email', 'max:255'];
            } else {
                if ($this->input && ! preg_match('#^https?://#i', $this->input)) {
                    $this->input = 'https://'.$this->input;
                }
                $rules['input'] = ['required', 'url', 'max:2048'];
            }
        }

        $this->validate($rules);

        $url = $platformEnum->buildUrl($this->input);

        if ($this->editingId) {
            $social = SocialLink::where('user_id', auth()->id())->findOrFail($this->editingId);
            $social->update(['url' => $url]);
        } else {
            $maxPosition = SocialLink::where('user_id', auth()->id())->max('position') ?? 0;
            SocialLink::create([
                'user_id' => auth()->id(),
                'platform' => $platformEnum,
                'url' => $url,
                'position' => $maxPosition + 1,
            ]);
        }

        $this->resetEditor();
        $this->showEditor = false;
        $this->dispatch('preview-refresh');
        $this->dispatch('toast', message: 'Rede social salva!');
    }

    public function deleteSocial(int $id): void
    {
        SocialLink::where('user_id', auth()->id())->findOrFail($id)->delete();
        $this->dispatch('preview-refresh');
        $this->dispatch('toast', message: 'Rede social removida.');
    }

    private function resetEditor(): void
    {
        $this->editingId = null;
        $this->platform = '';
        $this->input = '';
    }

    public function render()
    {
        $existingSocials = SocialLink::where('user_id', auth()->id())->ordered()->get();
        $existingPlatforms = $existingSocials->pluck('platform')->map->value->toArray();

        return view('livewire.builder.social-link-manager', [
            'socialLinks' => $existingSocials,
            'platforms' => SocialPlatform::cases(),
            'existingPlatforms' => $existingPlatforms,
        ]);
    }
}
