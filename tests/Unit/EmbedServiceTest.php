<?php

use App\Enums\EmbedType;
use App\Services\EmbedService;

beforeEach(function () {
    $this->service = new EmbedService;
});

test('parses youtube watch url', function () {
    $result = $this->service->parse('https://www.youtube.com/watch?v=dQw4w9WgXcQ');

    expect($result)->toBe('https://www.youtube-nocookie.com/embed/dQw4w9WgXcQ');
});

test('parses youtube short url', function () {
    $result = $this->service->parse('https://youtu.be/dQw4w9WgXcQ');

    expect($result)->toBe('https://www.youtube-nocookie.com/embed/dQw4w9WgXcQ');
});

test('parses youtube embed url', function () {
    $result = $this->service->parse('https://www.youtube.com/embed/dQw4w9WgXcQ');

    expect($result)->toBe('https://www.youtube-nocookie.com/embed/dQw4w9WgXcQ');
});

test('parses spotify track url', function () {
    $result = $this->service->parse('https://open.spotify.com/track/4cOdK2wGLETKBW3PvgPWqT');

    expect($result)->toBe('https://open.spotify.com/embed/track/4cOdK2wGLETKBW3PvgPWqT');
});

test('parses spotify playlist url', function () {
    $result = $this->service->parse('https://open.spotify.com/playlist/37i9dQZF1DXcBWIGoYBM5M');

    expect($result)->toBe('https://open.spotify.com/embed/playlist/37i9dQZF1DXcBWIGoYBM5M');
});

test('parses spotify album url', function () {
    $result = $this->service->parse('https://open.spotify.com/album/1DFixLWuPkv3KT3TnV35m3');

    expect($result)->toBe('https://open.spotify.com/embed/album/1DFixLWuPkv3KT3TnV35m3');
});

test('returns null for invalid url', function () {
    $result = $this->service->parse('https://example.com/not-a-video');

    expect($result)->toBeNull();
});

test('parses soundcloud track url', function () {
    $result = $this->service->parse('https://soundcloud.com/artist-name/track-name');

    expect($result)->toStartWith('https://w.soundcloud.com/player/?url=')
        ->and($result)->toContain(urlencode('https://soundcloud.com/artist-name/track-name'));
});

test('parses soundcloud set url', function () {
    $result = $this->service->parse('https://soundcloud.com/artist-name/sets/playlist-name');

    expect($result)->toStartWith('https://w.soundcloud.com/player/?url=')
        ->and($result)->toContain(urlencode('https://soundcloud.com/artist-name/sets/playlist-name'));
});

test('parses twitch channel url', function () {
    $result = $this->service->parse('https://www.twitch.tv/shroud');

    expect($result)->toContain('player.twitch.tv')
        ->and($result)->toContain('channel=shroud');
});

test('parses vimeo url', function () {
    $result = $this->service->parse('https://vimeo.com/12345678');

    expect($result)->toBe('https://player.vimeo.com/video/12345678?dnt=1');
});

test('parses tiktok video url', function () {
    $result = $this->service->parse('https://www.tiktok.com/@username/video/7123456789012345678');

    expect($result)->toBe('https://www.tiktok.com/embed/v2/7123456789012345678');
});

test('parses apple music album url', function () {
    $result = $this->service->parse('https://music.apple.com/br/album/album-name/1234567890');

    expect($result)->toBe('https://embed.music.apple.com/br/album/1234567890');
});

test('parses apple music playlist url', function () {
    $result = $this->service->parse('https://music.apple.com/us/playlist/playlist-name/pl.abc123');

    expect($result)->toBe('https://embed.music.apple.com/us/playlist/pl.abc123');
});

test('detectType returns correct type for each platform', function () {
    expect($this->service->detectType('https://youtube.com/watch?v=abc'))->toBe(EmbedType::YouTube)
        ->and($this->service->detectType('https://youtu.be/abc'))->toBe(EmbedType::YouTube)
        ->and($this->service->detectType('https://open.spotify.com/track/abc'))->toBe(EmbedType::Spotify)
        ->and($this->service->detectType('https://soundcloud.com/artist/track'))->toBe(EmbedType::SoundCloud)
        ->and($this->service->detectType('https://www.twitch.tv/channel'))->toBe(EmbedType::Twitch)
        ->and($this->service->detectType('https://vimeo.com/123'))->toBe(EmbedType::Vimeo)
        ->and($this->service->detectType('https://www.tiktok.com/@user/video/123'))->toBe(EmbedType::TikTok)
        ->and($this->service->detectType('https://music.apple.com/us/album/name/123'))->toBe(EmbedType::AppleMusic)
        ->and($this->service->detectType('https://example.com'))->toBeNull();
});

test('returns null for non-supported platform', function () {
    $result = $this->service->parse('https://example.com/not-a-video');

    expect($result)->toBeNull();
});
