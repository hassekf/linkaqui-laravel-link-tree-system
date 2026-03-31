<?php

namespace App\Enums;

enum EmbedType: string
{
    case YouTube = 'youtube';
    case Spotify = 'spotify';
    case SoundCloud = 'soundcloud';
    case Twitch = 'twitch';
    case Vimeo = 'vimeo';
    case TikTok = 'tiktok';
    case AppleMusic = 'apple_music';
}
