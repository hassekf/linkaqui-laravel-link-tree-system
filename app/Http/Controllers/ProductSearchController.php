<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProductSearchController extends Controller
{
    public function __invoke(Request $request, string $username): JsonResponse
    {
        $request->validate([
            'code' => ['required', 'string', 'min:3', 'max:10', 'regex:/^[A-Za-z0-9]+$/'],
        ]);

        $user = User::where('username', $username)
            ->where('is_active', true)
            ->firstOrFail();

        $code = strtoupper(trim($request->input('code')));

        $cacheKey = "product:{$user->id}:{$code}";

        $productData = Cache::remember($cacheKey, now()->addMinutes(15), function () use ($user, $code) {
            $product = Product::where('user_id', $user->id)
                ->where('code', $code)
                ->where('is_active', true)
                ->first();

            if (! $product) {
                return null;
            }

            return [
                'id' => $product->id,
                'name' => $product->name,
                'code' => $product->code,
                'image_url' => $product->image_url,
                'original_price' => $product->original_price ? number_format((float) $product->original_price, 2, ',', '.') : null,
                'sale_price' => $product->sale_price ? number_format((float) $product->sale_price, 2, ',', '.') : null,
                'discount_percentage' => $product->discount_percentage,
                'url' => $product->url,
            ];
        });

        if (! $productData) {
            return response()->json(['found' => false, 'message' => 'Produto nao encontrado']);
        }

        // Increment search count (outside cache)
        Product::where('id', $productData['id'])->increment('searches_count');

        // Don't expose internal ID to the frontend
        unset($productData['id']);

        return response()->json([
            'found' => true,
            'product' => $productData,
        ]);
    }
}
