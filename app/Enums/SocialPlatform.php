<?php

namespace App\Enums;

enum SocialPlatform: string
{
    case Instagram = 'instagram';
    case Twitter = 'twitter';
    case GitHub = 'github';
    case LinkedIn = 'linkedin';
    case TikTok = 'tiktok';
    case YouTube = 'youtube';
    case Facebook = 'facebook';
    case WhatsApp = 'whatsapp';
    case Telegram = 'telegram';
    case Discord = 'discord';
    case Twitch = 'twitch';
    case Spotify = 'spotify';
    case Email = 'email';
    case Website = 'website';

    public function label(): string
    {
        return match ($this) {
            self::Instagram => 'Instagram',
            self::Twitter => 'Twitter / X',
            self::GitHub => 'GitHub',
            self::LinkedIn => 'LinkedIn',
            self::TikTok => 'TikTok',
            self::YouTube => 'YouTube',
            self::Facebook => 'Facebook',
            self::WhatsApp => 'WhatsApp',
            self::Telegram => 'Telegram',
            self::Discord => 'Discord',
            self::Twitch => 'Twitch',
            self::Spotify => 'Spotify',
            self::Email => 'E-mail',
            self::Website => 'Website',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Instagram => '#E4405F',
            self::Twitter => '#1DA1F2',
            self::GitHub => '#181717',
            self::LinkedIn => '#0A66C2',
            self::TikTok => '#000000',
            self::YouTube => '#FF0000',
            self::Facebook => '#1877F2',
            self::WhatsApp => '#25D366',
            self::Telegram => '#26A5E4',
            self::Discord => '#5865F2',
            self::Twitch => '#9146FF',
            self::Spotify => '#1DB954',
            self::Email => '#EA4335',
            self::Website => '#6366F1',
        };
    }

    public function baseUrl(): ?string
    {
        return match ($this) {
            self::Instagram => 'https://instagram.com/',
            self::Twitter => 'https://x.com/',
            self::GitHub => 'https://github.com/',
            self::LinkedIn => 'https://linkedin.com/in/',
            self::TikTok => 'https://tiktok.com/@',
            self::YouTube => 'https://youtube.com/@',
            self::Facebook => 'https://facebook.com/',
            self::WhatsApp => 'https://wa.me/',
            self::Telegram => 'https://t.me/',
            self::Discord => 'https://discord.gg/',
            self::Twitch => 'https://twitch.tv/',
            self::Spotify => 'https://open.spotify.com/user/',
            self::Email, self::Website => null,
        };
    }

    public function usesUsername(): bool
    {
        return $this->baseUrl() !== null && ! in_array($this, [self::Discord, self::WhatsApp]);
    }

    public function inputLabel(): string
    {
        return match (true) {
            $this === self::Email => 'E-mail',
            $this === self::Website => 'URL do site',
            $this === self::WhatsApp => 'Número com DDD (ex: 5511999999999)',
            $this === self::Discord => 'Link do servidor ou convite',
            default => 'Nome de usuário',
        };
    }

    public function inputPlaceholder(): string
    {
        return match ($this) {
            self::Instagram => 'Ex: seuusuario (sem @)',
            self::Twitter => 'Ex: seuusuario (sem @)',
            self::GitHub => 'Ex: seuusuario',
            self::TikTok => 'Ex: seuusuario (sem @)',
            self::YouTube => 'Ex: seucanal',
            self::Facebook => 'Ex: seuusuario ou pagina',
            self::Twitch => 'Ex: seucanal',
            self::Telegram => 'Ex: seuusuario',
            self::LinkedIn => 'Ex: seu-perfil',
            self::Spotify => 'Ex: seu-id-spotify',
            self::WhatsApp => 'Ex: 5511999999999',
            self::Discord => 'Ex: discord.gg/convite',
            self::Email => 'Ex: seu@email.com',
            self::Website => 'Ex: seusite.com',
        };
    }

    public function buildUrl(string $input): string
    {
        if ($this === self::Email) {
            return 'mailto:'.$input;
        }

        if (! $this->usesUsername()) {
            return $input;
        }

        $input = ltrim($input, '@');

        return $this->baseUrl().$input;
    }

    public function extractUsername(string $url): string
    {
        $baseUrl = $this->baseUrl();

        if (! $baseUrl || ! $this->usesUsername()) {
            return $url;
        }

        if (str_starts_with($url, $baseUrl)) {
            return rtrim(str_replace($baseUrl, '', $url), '/');
        }

        if (str_starts_with($url, 'mailto:')) {
            return str_replace('mailto:', '', $url);
        }

        return ltrim($url, '@');
    }
}
