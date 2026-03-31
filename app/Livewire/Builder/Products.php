<?php

namespace App\Livewire\Builder;

use App\Models\Product;
use App\Services\ImageService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.builder')]
#[Title('Produtos')]
class Products extends Component
{
    use WithFileUploads;

    public bool $showEditor = false;

    #[Locked]
    public ?int $editingProductId = null;

    public string $name = '';

    public string $code = '';

    public string $originalPrice = '';

    public string $salePrice = '';

    public string $productUrl = '';

    public string $search = '';

    public bool $productSearchEnabled = false;

    #[Validate('nullable|image|mimes:jpg,jpeg,png,webp|max:2048')]
    public $productImage;

    public function mount(): void
    {
        $this->productSearchEnabled = Auth::user()->settings['product_search_enabled'] ?? false;
    }

    public function updatedSearch(): void
    {
        // triggers re-render
    }

    public function addProduct(): void
    {
        $this->resetEditor();
        $this->code = Product::generateCode();
        $this->showEditor = true;
    }

    public function editProduct(int $id): void
    {
        $product = Product::where('user_id', Auth::id())->findOrFail($id);

        $this->editingProductId = $product->id;
        $this->name = $product->name;
        $this->code = $product->code;
        $this->originalPrice = $product->original_price ? (string) $product->original_price : '';
        $this->salePrice = $product->sale_price ? (string) $product->sale_price : '';
        $this->productUrl = $product->url;
        $this->showEditor = true;
    }

    public function saveProduct(): void
    {
        $this->validate([
            'name' => ['required', 'string', 'max:150'],
            'code' => ['required', 'string', 'max:10'],
            'originalPrice' => ['nullable', 'numeric', 'min:0', 'max:99999999.99'],
            'salePrice' => ['nullable', 'numeric', 'min:0', 'max:99999999.99'],
            'productUrl' => ['required', 'string', 'max:2048'],
        ]);

        if ($this->productUrl && ! preg_match('#^https?://#i', $this->productUrl)) {
            $this->productUrl = 'https://'.$this->productUrl;
        }

        $this->validate([
            'productUrl' => ['required', 'url', 'max:2048'],
        ]);

        $imagePath = null;
        if ($this->productImage) {
            $imagePath = app(ImageService::class)->storeProductImage($this->productImage);
        }

        $data = [
            'name' => $this->name,
            'code' => strtoupper($this->code),
            'original_price' => $this->originalPrice !== '' ? $this->originalPrice : null,
            'sale_price' => $this->salePrice !== '' ? $this->salePrice : null,
            'url' => $this->productUrl,
        ];

        if ($imagePath) {
            $data['image_path'] = $imagePath;
        }

        if ($this->editingProductId) {
            $product = Product::where('user_id', Auth::id())->findOrFail($this->editingProductId);

            if ($imagePath && $product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }

            $this->clearProductCache($product->code);
            $product->update($data);
            // Clear cache for new code if changed
            if ($product->code !== $data['code']) {
                $this->clearProductCache($data['code']);
            }
        } else {
            $data['user_id'] = Auth::id();
            Product::create($data);
        }

        $this->clearProductCache($data['code']);
        $this->closeEditor();
        $this->dispatch('preview-refresh');
        $this->dispatch('toast', message: 'Produto salvo!');
    }

    public function deleteProduct(int $id): void
    {
        $product = Product::where('user_id', Auth::id())->findOrFail($id);

        $this->clearProductCache($product->code);

        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }

        $product->delete();
        $this->dispatch('preview-refresh');
        $this->dispatch('toast', message: 'Produto removido.');
    }

    public function toggleProduct(int $id): void
    {
        $product = Product::where('user_id', Auth::id())->findOrFail($id);
        $this->clearProductCache($product->code);
        $product->update(['is_active' => ! $product->is_active]);
        $this->dispatch('preview-refresh');
        $this->dispatch('toast', message: $product->fresh()->is_active ? 'Produto ativado.' : 'Produto desativado.');
    }

    public function toggleProductSearch(): void
    {
        $this->productSearchEnabled = ! $this->productSearchEnabled;

        $user = Auth::user();
        $settings = $user->settings ?? [];
        $settings['product_search_enabled'] = $this->productSearchEnabled;
        $user->update(['settings' => $settings]);

        $this->dispatch('preview-refresh');
        $this->dispatch('toast', message: $this->productSearchEnabled ? 'Busca de produtos ativada!' : 'Busca de produtos desativada.');
    }

    public function regenerateCode(): void
    {
        $this->code = Product::generateCode();
    }

    public function closeEditor(): void
    {
        $this->resetEditor();
        $this->showEditor = false;
    }

    private function clearProductCache(string $code): void
    {
        Cache::forget('product:'.Auth::id().':'.strtoupper($code));
    }

    private function resetEditor(): void
    {
        $this->editingProductId = null;
        $this->name = '';
        $this->code = '';
        $this->originalPrice = '';
        $this->salePrice = '';
        $this->productUrl = '';
        $this->productImage = null;
        $this->resetValidation();
    }

    public function render()
    {
        $query = Product::where('user_id', Auth::id());

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('code', 'like', '%'.strtoupper($this->search).'%');
            });
        }

        $products = $query->orderByDesc('created_at')->get();

        $topProducts = Product::where('user_id', Auth::id())
            ->where('is_active', true)
            ->where('updated_at', '>=', now()->subWeek())
            ->orderByDesc('clicks_count')
            ->take(5)
            ->get()
            ->filter(fn ($p) => $p->clicks_count > 0 || $p->searches_count > 0);

        return view('livewire.builder.products', [
            'products' => $products,
            'topProducts' => $topProducts,
        ]);
    }
}
