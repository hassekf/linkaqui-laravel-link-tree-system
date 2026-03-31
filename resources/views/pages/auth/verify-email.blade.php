<x-layouts::auth title="Verificação de e-mail">
    <div class="mt-4 flex flex-col gap-6">
        <p class="text-center text-sm" style="color: #9ca3af;">
            Por favor, verifique seu e-mail clicando no link que acabamos de enviar.
        </p>

        @if (session('status') == 'verification-link-sent')
            <p class="text-center text-sm font-medium text-green-400">
                Um novo link de verificação foi enviado para o e-mail informado no cadastro.
            </p>
        @endif

        <div class="flex flex-col items-center justify-between space-y-3">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <flux:button type="submit" variant="primary" class="w-full">
                    Reenviar e-mail de verificação
                </flux:button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <flux:button variant="ghost" type="submit" class="text-sm cursor-pointer" data-test="logout-button">
                    Sair
                </flux:button>
            </form>
        </div>
    </div>
</x-layouts::auth>
