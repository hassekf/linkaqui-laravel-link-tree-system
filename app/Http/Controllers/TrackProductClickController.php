<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TrackProductClickController extends Controller
{
    public function __invoke(Request $request, string $username, string $code): JsonResponse
    {
        $user = User::where('username', $username)
            ->where('is_active', true)
            ->firstOrFail();

        $product = Product::where('user_id', $user->id)
            ->where('code', $code)
            ->firstOrFail();

        $product->increment('clicks_count');

        return response()->json(['url' => $product->url]);
    }
}
