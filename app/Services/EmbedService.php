<?php

namespace App\Services;

use App\Enums\EmbedType;

class EmbedService
{
    /**
     * Parse a YouTube URL and return a privacy-enhanced embed URL.
     */
    public function parseYouTubeUrl(string $url): ?string
    {
        $patterns = [
            '/(?:youtube\.com\/watch\?v=|youtube\.com\/watch\?.+&v=)([a-zA-Z0-9_-]{11})/',
            '/youtu\.be\/([a-zA-Z0-9_-]{11})/',
            '/youtube\.com\/embed\/([a-zA-Z0-9_-]{11})/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                return 'https://www.youtube-nocookie.com/embed/'.$matches[1];
            }
        }

        return null;
    }

    /**
     * Parse a Spotify URL and return an embed URL.
     */
    public function parseSpotifyUrl(string $url): ?string
    {
        if (preg_match('/open\.spotify\.com\/(?:intl-[a-z]{2}\/)?(?:embed\/)?(track|album|playlist|episode)\/([a-zA-Z0-9]+)/', $url, $matches)) {
            return 'https://open.spotify.com/embed/'.$matches[1].'/'.$matches[2];
        }

        return null;
    }

    /**
     * Parse a SoundCloud URL and return an embed URL.
     */
    public function parseSoundCloudUrl(string $url): ?string
    {
        if (preg_match('#soundcloud\.com/[\w-]+(/sets)?/[\w-]+#', $url)) {
            return 'https://w.soundcloud.com/player/?url='.urlencode($url).'&color=%237c5cfc&auto_play=false&hide_related=true&show_comments=false&show_user=true&show_reposts=false&show_teaser=false';
        }

        return null;
    }

    /**
     * Parse a Twitch URL and return an embed URL.
     */
    public function parseTwitchUrl(string $url): ?string
    {
        if (preg_match('#twitch\.tv/(\w+)/?$#', $url, $matches)) {
            $channel = $matches[1];

            return "https://player.twitch.tv/?channel={$channel}&muted=true";
        }

        return null;
    }

    /**
     * Parse a Vimeo URL and return an embed URL.
     */
    public function parseVimeoUrl(string $url): ?string
    {
        if (preg_match('#vimeo\.com/(?:video/)?(\d+)#', $url, $matches)) {
            return 'https://player.vimeo.com/video/'.$matches[1].'?dnt=1';
        }

        return null;
    }

    /**
     * Parse a TikTok URL and return an embed URL.
     */
    public function parseTikTokUrl(string $url): ?string
    {
        if (preg_match('#tiktok\.com/@[\w.]+/video/(\d+)#', $url, $matches)) {
            return 'https://www.tiktok.com/embed/v2/'.$matches[1];
        }

        return null;
    }

    /**
     * Parse an Apple Music URL and return an embed URL.
     */
    public function parseAppleMusicUrl(string $url): ?string
    {
        if (preg_match('#music\.apple\.com/([\w-]+)/(album|playlist|song)/[\w-]+/([\w.]+)#', $url, $matches)) {
            $type = $matches[2] === 'song' ? 'album' : $matches[2];

            return "https://embed.music.apple.com/{$matches[1]}/{$type}/{$matches[3]}";
        }

        return null;
    }

    /**
     * Auto-detect the platform and return the appropriate embed URL.
     */
    public function parse(string $url): ?string
    {
        if (str_contains($url, 'youtube.com') || str_contains($url, 'youtu.be')) {
            return $this->parseYouTubeUrl($url);
        }

        if (str_contains($url, 'spotify.com')) {
            return $this->parseSpotifyUrl($url);
        }

        if (str_contains($url, 'soundcloud.com')) {
            return $this->parseSoundCloudUrl($url);
        }

        if (str_contains($url, 'twitch.tv')) {
            return $this->parseTwitchUrl($url);
        }

        if (str_contains($url, 'vimeo.com')) {
            return $this->parseVimeoUrl($url);
        }

        if (str_contains($url, 'tiktok.com')) {
            return $this->parseTikTokUrl($url);
        }

        if (str_contains($url, 'music.apple.com')) {
            return $this->parseAppleMusicUrl($url);
        }

        return null;
    }

    /**
     * Detect the embed type from a URL.
     */
    public function detectType(string $url): ?EmbedType
    {
        if (str_contains($url, 'youtube.com') || str_contains($url, 'youtu.be')) {
            return EmbedType::YouTube;
        }
        if (str_contains($url, 'spotify.com')) {
            return EmbedType::Spotify;
        }
        if (str_contains($url, 'soundcloud.com')) {
            return EmbedType::SoundCloud;
        }
        if (str_contains($url, 'twitch.tv')) {
            return EmbedType::Twitch;
        }
        if (str_contains($url, 'vimeo.com')) {
            return EmbedType::Vimeo;
        }
        if (str_contains($url, 'tiktok.com')) {
            return EmbedType::TikTok;
        }
        if (str_contains($url, 'music.apple.com')) {
            return EmbedType::AppleMusic;
        }

        return null;
    }
}
