<?php

use App\Models\User;

test('landing page renders for guests', function () {
    $this->get('/')
        ->assertOk()
        ->assertSee('LinkAqui')
        ->assertSee('Sua página de links')
        ->assertSee('Criar minha página grátis')
        ->assertSee('Entrar')
        ->assertSee('Criar conta');
});

test('landing page shows all feature cards', function () {
    $this->get('/')
        ->assertOk()
        ->assertSee('Links personalizáveis')
        ->assertSee('Redes sociais')
        ->assertSee('Embeds')
        ->assertSee('Temas')
        ->assertSee('Analytics')
        ->assertSee('Open Source');
});

test('landing page shows how it works section', function () {
    $this->get('/')
        ->assertOk()
        ->assertSee('Como funciona')
        ->assertSee('Cadastre-se')
        ->assertSee('Personalize')
        ->assertSee('Compartilhe');
});

test('authenticated users are redirected from landing to builder', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/')
        ->assertRedirect(route('builder'));
});
