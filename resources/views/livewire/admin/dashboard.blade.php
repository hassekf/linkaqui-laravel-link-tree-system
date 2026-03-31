<div class="p-6 space-y-6">
    <flux:heading size="xl">Dashboard</flux:heading>

    <!-- Period filter -->
    <div class="flex items-center gap-2">
        <flux:button size="sm" :variant="$period === '7' ? 'primary' : 'ghost'" wire:click="$set('period', '7')">7 dias</flux:button>
        <flux:button size="sm" :variant="$period === '30' ? 'primary' : 'ghost'" wire:click="$set('period', '30')">30 dias</flux:button>
        <flux:button size="sm" :variant="$period === '90' ? 'primary' : 'ghost'" wire:click="$set('period', '90')">90 dias</flux:button>
        <flux:button size="sm" :variant="$period === 'all' ? 'primary' : 'ghost'" wire:click="$set('period', 'all')">Tudo</flux:button>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <flux:card class="space-y-1">
            <flux:text>Total de Usuários</flux:text>
            <flux:heading size="xl">{{ number_format($this->totalUsers) }}</flux:heading>
        </flux:card>

        <flux:card class="space-y-1">
            <flux:text>Total de Links</flux:text>
            <flux:heading size="xl">{{ number_format($this->totalLinks) }}</flux:heading>
        </flux:card>

        <flux:card class="space-y-1">
            <flux:text>Visitas (período)</flux:text>
            <flux:heading size="xl">{{ number_format($this->totalVisits) }}</flux:heading>
        </flux:card>

        <flux:card class="space-y-1">
            <flux:text>Cliques (período)</flux:text>
            <flux:heading size="xl">{{ number_format($this->totalClicks) }}</flux:heading>
        </flux:card>
    </div>

    <!-- Visits over time chart -->
    <flux:card class="space-y-4">
        <flux:heading size="lg">Visitas por dia</flux:heading>
        @if($this->visitsOverTime->isNotEmpty())
        <div class="flex items-end gap-1 h-40">
            @php($maxV = $this->visitsOverTime->max('count') ?: 1)
            @foreach($this->visitsOverTime as $day)
            <div class="flex-1 rounded-t bg-accent hover:opacity-80 transition-opacity"
                 style="height: {{ ($day->count / $maxV) * 100 }}%; min-height: 2px;"
                 title="{{ \Carbon\Carbon::parse($day->date)->format('d/m') }}: {{ $day->count }} visitas"></div>
            @endforeach
        </div>
        @else
        <flux:text class="text-center py-8">Sem dados no período</flux:text>
        @endif
    </flux:card>

    <!-- Top users -->
    <flux:card class="space-y-4">
        <flux:heading size="lg">Top usuários</flux:heading>
        <flux:table>
            <flux:table.columns>
                <flux:table.column>Usuário</flux:table.column>
                <flux:table.column>Visitas</flux:table.column>
                <flux:table.column align="end">Ação</flux:table.column>
            </flux:table.columns>
            <flux:table.rows>
                @forelse($this->topUsers as $user)
                <flux:table.row :key="$user->id">
                    <flux:table.cell class="flex items-center gap-2">
                        <img src="{{ $user->avatar_url }}" class="w-8 h-8 rounded-full object-cover" />
                        <div>
                            <div class="font-medium">{{ $user->name }}</div>
                            <flux:text size="sm">{{ '@' . $user->username }}</flux:text>
                        </div>
                    </flux:table.cell>
                    <flux:table.cell>{{ $user->visits_count }}</flux:table.cell>
                    <flux:table.cell align="end">
                        <flux:button variant="ghost" size="sm" icon="eye" :href="route('admin.users.show', $user)" wire:navigate />
                    </flux:table.cell>
                </flux:table.row>
                @empty
                <flux:table.row>
                    <flux:table.cell colspan="3">
                        <flux:text class="text-center">Nenhum dado disponível.</flux:text>
                    </flux:table.cell>
                </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>
    </flux:card>

    <!-- Recent users -->
    <flux:card class="space-y-4">
        <flux:heading size="lg">Cadastros Recentes</flux:heading>

        <flux:table>
            <flux:table.columns>
                <flux:table.column>Nome</flux:table.column>
                <flux:table.column>Username</flux:table.column>
                <flux:table.column>Email</flux:table.column>
                <flux:table.column>Status</flux:table.column>
                <flux:table.column>Cadastro</flux:table.column>
                <flux:table.column align="end">Ação</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse ($this->recentUsers as $user)
                    <flux:table.row :key="$user->id">
                        <flux:table.cell class="flex items-center gap-2">
                            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-8 h-8 rounded-full object-cover" />
                            {{ $user->name }}
                        </flux:table.cell>
                        <flux:table.cell>{{ $user->username }}</flux:table.cell>
                        <flux:table.cell>{{ $user->email }}</flux:table.cell>
                        <flux:table.cell>
                            <flux:badge size="sm" :color="$user->is_active ? 'green' : 'red'">
                                {{ $user->is_active ? 'Ativo' : 'Inativo' }}
                            </flux:badge>
                        </flux:table.cell>
                        <flux:table.cell class="whitespace-nowrap">{{ $user->created_at->format('d/m/Y H:i') }}</flux:table.cell>
                        <flux:table.cell align="end">
                            <flux:button variant="ghost" size="sm" icon="eye" :href="route('admin.users.show', $user)" wire:navigate />
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
    </flux:card>
</div>
