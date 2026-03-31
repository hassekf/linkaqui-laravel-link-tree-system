<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Gerenciar Usuários')]
class UserManager extends Component
{
    use WithPagination;

    public string $search = '';

    public ?int $confirmingDeleteId = null;

    public bool $showEditModal = false;

    #[Locked]
    public ?int $editingUserId = null;

    public string $editName = '';

    public string $editUsername = '';

    public string $editEmail = '';

    public string $editBio = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    /** @return LengthAwarePaginator<int, User> */
    #[Computed]
    public function users(): LengthAwarePaginator
    {
        return User::query()
            ->when($this->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('username', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(15);
    }

    public function toggleActive(int $userId): void
    {
        $user = User::findOrFail($userId);
        $user->update(['is_active' => ! $user->is_active]);
        unset($this->users);
    }

    public function toggleAdmin(int $userId): void
    {
        $user = User::findOrFail($userId);

        if ($user->id === auth()->id()) {
            return;
        }

        $user->update(['is_admin' => ! $user->is_admin]);
        unset($this->users);
    }

    public function confirmDelete(int $userId): void
    {
        $this->confirmingDeleteId = $userId;
    }

    public function cancelDelete(): void
    {
        $this->confirmingDeleteId = null;
    }

    public function deleteUser(): void
    {
        if (! $this->confirmingDeleteId) {
            return;
        }

        $user = User::findOrFail($this->confirmingDeleteId);

        if ($user->id === auth()->id()) {
            return;
        }

        $user->delete();
        $this->confirmingDeleteId = null;
        unset($this->users);
    }

    public function editUser(int $userId): void
    {
        $user = User::findOrFail($userId);
        $this->editingUserId = $user->id;
        $this->editName = $user->name;
        $this->editUsername = $user->username;
        $this->editEmail = $user->email;
        $this->editBio = $user->bio ?? '';
        $this->showEditModal = true;
    }

    public function saveUser(): void
    {
        $this->validate([
            'editName' => ['required', 'string', 'max:255'],
            'editUsername' => ['required', 'string', 'min:3', 'max:30', 'regex:/^[a-z0-9][a-z0-9_-]*[a-z0-9]$/', Rule::unique(User::class, 'username')->ignore($this->editingUserId)],
            'editEmail' => ['required', 'email', 'max:255', Rule::unique(User::class, 'email')->ignore($this->editingUserId)],
            'editBio' => ['nullable', 'string', 'max:255'],
        ]);

        $user = User::findOrFail($this->editingUserId);
        $user->update([
            'name' => $this->editName,
            'username' => $this->editUsername,
            'email' => $this->editEmail,
            'bio' => $this->editBio,
        ]);

        $this->showEditModal = false;
        $this->editingUserId = null;
        unset($this->users);
    }

    public function closeEditModal(): void
    {
        $this->showEditModal = false;
        $this->editingUserId = null;
    }

    public function render()
    {
        return view('livewire.admin.user-manager');
    }
}
