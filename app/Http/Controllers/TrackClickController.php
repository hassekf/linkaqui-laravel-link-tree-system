<?php

namespace App\Http\Controllers;

use App\Jobs\RecordClick;
use App\Models\Link;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TrackClickController extends Controller
{
    public function __invoke(Request $request, string $username, Link $link): JsonResponse
    {
        RecordClick::dispatch(
            linkId: $link->id,
            ip: $request->ip(),
        );

        return response()->json(['url' => $link->url]);
    }
}
