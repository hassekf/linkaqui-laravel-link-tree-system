<div class="p-6 space-y-6">
    <flux:heading size="xl">Usuários</flux:heading>

    <div class="flex items-center gap-4">
        <flux:input icon="magnifying-glass" placeholder="Buscar por nome, username ou email..." wire:model.live.debounce.300ms="search" class="max-w-md" />
    </div>

    <flux:table :paginate="$this->users">
        <flux:table.columns>
            <flux:table.column>Usuário</flux:table.column>
            <flux:table.column>Email</flux:table.column>
            <flux:table.column>Status</flux:table.column>
            <flux:table.column>Admin</flux:table.column>
            <flux:table.column>Cadastro</flux:table.column>
            <flux:table.column align="end">Ações</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @forelse ($this->users as $user)
                <flux:table.row :key="$user->id">
                    <flux:table.cell class="flex items-center gap-2">
                        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-8 h-8 rounded-full object-cover" />
                        <div>
                            <div class="font-medium">{{ $user->name }}</div>
                            <flux:text size="sm">{{ '@' . $user->username }}</flux:text>
                        </div>
                    </flux:table.cell>
                    <flux:table.cell>{{ $user->email }}</flux:table.cell>
                    <flux:table.cell>
                        <flux:switch wire:click="toggleActive({{ $user->id }})" :checked="$user->is_active" />
                    </flux:table.cell>
                    <flux:table.cell>
                        <flux:switch
                            wire:click="toggleAdmin({{ $user->id }})"
                            :checked="$user->is_admin"
                            :disabled="$user->id === auth()->id()"
                        />
                    </flux:table.cell>
                    <flux:table.cell class="whitespace-nowrap">{{ $user->created_at->format('d/m/Y') }}</flux:table.cell>
                    <flux:table.cell align="end">
                        <flux:button variant="ghost" size="sm" icon="eye" :href="route('admin.users.show', $user)" wire:navigate />
                        <flux:button variant="ghost" size="sm" icon="pencil" wire:click="editUser({{ $user->id }})" />
                        @if ($user->id !== auth()->id())
                            <flux:button variant="danger" size="sm" icon="trash" wire:click="confirmDelete({{ $user->id }})" />
                        @endif
                    </flux:table.cell>
                </flux:table.row>
            @empty
                <flux:table.row>
                    <flux:table.cell colspan="6">
                        <flux:text class="text-center">Nenhum usuário encontrado.</flux:text>
                    </flux:table.cell>
                </flux:table.row>
            @endforelse
        </flux:table.rows>
    </flux:table>

    <flux:modal wire:model.self="confirmingDeleteId" class="space-y-6">
        <flux:heading size="lg">Confirmar Exclusão</flux:heading>
        <flux:text>Tem certeza que deseja excluir este usuário? Esta ação não pode ser desfeita.</flux:text>
        <div class="flex gap-2 justify-end">
            <flux:button variant="ghost" wire:click="cancelDelete">Cancelar</flux:button>
            <flux:button variant="danger" wire:click="deleteUser">Excluir</flux:button>
        </div>
    </flux:modal>

    @if($showEditModal)
    <flux:modal wire:model.self="showEditModal" class="space-y-6 max-w-lg">
        <flux:heading size="lg">Editar Usuário</flux:heading>

        <flux:input label="Nome" wire:model="editName" />
        <flux:input label="Username" wire:model="editUsername" />
        <flux:input label="Email" type="email" wire:model="editEmail" />
        <flux:textarea label="Bio" wire:model="editBio" rows="3" />

        <div class="flex gap-2 justify-end">
            <flux:button variant="ghost" wire:click="closeEditModal">Cancelar</flux:button>
            <flux:button variant="primary" wire:click="saveUser">Salvar</flux:button>
        </div>
    </flux:modal>
    @endif
</div>
