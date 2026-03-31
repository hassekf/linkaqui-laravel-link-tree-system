<?php

use App\Livewire\Builder\Products;
use App\Models\Product;
use App\Models\Theme;
use App\Models\User;
use Illuminate\Support\Facades\Queue;
use Livewire\Livewire;

beforeEach(function () {
    Queue::fake();
    Theme::factory()->default()->create();
});

test('product search returns correct product by code', function () {
    $user = User::factory()->create(['username' => 'searchuser', 'settings' => ['product_search_enabled' => true]]);
    $product = Product::factory()->for($user)->create(['code' => 'ABC123', 'is_active' => true]);

    $response = $this->get(route('profile.product.search', ['username' => 'searchuser', 'code' => 'ABC123']));

    $response->assertOk()
        ->assertJson([
            'found' => true,
            'product' => [
                'name' => $product->name,
                'code' => 'ABC123',
            ],
        ]);
});

test('product search returns not found for invalid code', function () {
    $user = User::factory()->create(['username' => 'searchuser2']);

    $response = $this->get(route('profile.product.search', ['username' => 'searchuser2', 'code' => 'XXXXXX']));

    $response->assertOk()
        ->assertJson([
            'found' => false,
            'message' => 'Produto nao encontrado',
        ]);
});

test('product search increments searches_count', function () {
    $user = User::factory()->create(['username' => 'countuser']);
    $product = Product::factory()->for($user)->create(['code' => 'CNT001', 'searches_count' => 0, 'is_active' => true]);

    $this->get(route('profile.product.search', ['username' => 'countuser', 'code' => 'CNT001']));

    expect($product->fresh()->searches_count)->toBe(1);
});

test('product click increments clicks_count', function () {
    $user = User::factory()->create(['username' => 'clickuser']);
    $product = Product::factory()->for($user)->create(['code' => 'CLK001', 'clicks_count' => 0]);

    $response = $this->post(route('profile.product.click', ['username' => 'clickuser', 'code' => 'CLK001']));

    $response->assertOk()
        ->assertJson(['url' => $product->url]);

    expect($product->fresh()->clicks_count)->toBe(1);
});

test('products page requires authentication', function () {
    $response = $this->get(route('products'));

    $response->assertRedirect(route('login'));
});

test('user can create product', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(Products::class)
        ->call('addProduct')
        ->set('name', 'Produto Teste')
        ->set('productUrl', 'https://example.com/produto')
        ->set('originalPrice', '99.90')
        ->set('salePrice', '49.90')
        ->call('saveProduct');

    expect(Product::where('user_id', $user->id)->count())->toBe(1);

    $product = Product::where('user_id', $user->id)->first();
    expect($product->name)->toBe('Produto Teste')
        ->and($product->url)->toBe('https://example.com/produto')
        ->and((float) $product->original_price)->toBe(99.90)
        ->and((float) $product->sale_price)->toBe(49.90)
        ->and($product->code)->toHaveLength(6);
});

test('user can edit product', function () {
    $user = User::factory()->create();
    $product = Product::factory()->for($user)->create(['name' => 'Old Name']);

    Livewire::actingAs($user)
        ->test(Products::class)
        ->call('editProduct', $product->id)
        ->set('name', 'New Name')
        ->call('saveProduct');

    expect($product->fresh()->name)->toBe('New Name');
});

test('user can delete product', function () {
    $user = User::factory()->create();
    $product = Product::factory()->for($user)->create();

    Livewire::actingAs($user)
        ->test(Products::class)
        ->call('deleteProduct', $product->id);

    expect(Product::find($product->id))->toBeNull();
});

test('auto-generated code is unique and valid format', function () {
    $codes = collect();

    for ($i = 0; $i < 10; $i++) {
        $code = Product::generateCode();
        expect($code)->toHaveLength(6)
            ->and($code)->toMatch('/^[A-Z0-9]+$/');
        $codes->push($code);
    }

    expect($codes->unique()->count())->toBe(10);
});

test('product search only works for active products', function () {
    $user = User::factory()->create(['username' => 'activetest']);
    Product::factory()->for($user)->create(['code' => 'INA001', 'is_active' => false]);

    $response = $this->get(route('profile.product.search', ['username' => 'activetest', 'code' => 'INA001']));

    $response->assertOk()
        ->assertJson([
            'found' => false,
            'message' => 'Produto nao encontrado',
        ]);
});
