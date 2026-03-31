<?php

namespace App\Livewire\Builder;

use App\Concerns\ProfileValidationRules;
use App\Services\ImageService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProfileSettings extends Component
{
    use ProfileValidationRules, WithFileUploads;

    public string $name = '';

    public string $username = '';

    public string $bio = '';

    public string $socialPosition = 'bottom';

    public string $fontFamily = '';

    #[Validate('nullable|image|mimes:jpg,jpeg,png,webp|max:2048')]
    public $avatar;

    public function mount(): void
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->username = $user->username;
        $this->bio = $user->bio ?? '';
        $this->socialPosition = $user->social_position;
        $this->fontFamily = $user->settings['font_family'] ?? '';
    }

    public function save(): void
    {
        $user = Auth::user();

        $this->validate([
            'name' => $this->nameRules(),
            'username' => $this->usernameRules($user->id),
            'bio' => ['nullable', 'string', 'max:255'],
        ]);

        $data = [
            'name' => $this->name,
            'username' => $this->username,
            'bio' => $this->bio,
        ];

        if ($this->avatar) {
            if ($user->avatar_path) {
                Storage::disk('public')->delete($user->avatar_path);
            }
            $data['avatar_path'] = app(ImageService::class)->storeAvatar($this->avatar);
            $this->avatar = null;
        }

        $user->update($data);

        $this->dispatch('profile-updated');
        $this->dispatch('preview-refresh');
        $this->dispatch('toast', message: 'Perfil salvo!');
    }

    public function updateSocialPosition(string $position): void
    {
        $this->updateSetting('social_position', $position);
        $this->socialPosition = $position;
        $this->dispatch('preview-refresh');
        $this->dispatch('toast', message: 'Posicao atualizada!');
    }

    public function updatedFontFamily(): void
    {
        $this->updateSetting('font_family', $this->fontFamily ?: null);
        $this->dispatch('preview-refresh');
        $this->dispatch('toast', message: 'Fonte atualizada!');
    }

    private function updateSetting(string $key, mixed $value): void
    {
        $user = Auth::user();
        $settings = $user->settings ?? [];

        if ($value === null) {
            unset($settings[$key]);
        } else {
            $settings[$key] = $value;
        }

        $user->update(['settings' => $settings]);
    }

    public function render()
    {
        return view('livewire.builder.profile-settings');
    }
}
