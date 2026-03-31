<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ContentSecurityPolicy
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set(
            'Content-Security-Policy',
            "frame-src 'self' https://www.youtube-nocookie.com https://open.spotify.com https://w.soundcloud.com https://player.twitch.tv https://player.vimeo.com https://www.tiktok.com https://embed.music.apple.com; ".
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net; ".
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; ".
            "img-src 'self' data: https: blob:; ".
            "font-src 'self' data: https://fonts.gstatic.com; ".
            "connect-src 'self';"
        );

        return $response;
    }
}
