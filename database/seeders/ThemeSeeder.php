<?php

namespace Database\Seeders;

use App\Models\Theme;
use Illuminate\Database\Seeder;

class ThemeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $themes = [
            [
                'name' => 'Midnight Glow',
                'slug' => 'midnight-glow',
                'description' => 'Tema escuro com gradiente mesh em roxo, ciano e rosa.',
                'is_default' => true,
                'config' => [
                    'background' => '#0a0a0f',
                    'backgroundType' => 'mesh',
                    'meshColors' => ['#7c5cfc', '#00d4ff', '#ff6bcb'],
                    'grain' => true,
                    'cardBg' => 'rgba(255,255,255,0.05)',
                    'cardBorder' => 'rgba(255,255,255,0.1)',
                    'cardBlur' => true,
                    'textPrimary' => '#ffffff',
                    'textSecondary' => '#9ca3af',
                    'nameGradient' => ['#ffffff', '#a78bfa'],
                    'avatarRingGradient' => ['#7c5cfc', '#00d4ff', '#ff6bcb'],
                    'statusIndicator' => true,
                    'fontFamily' => 'Inter',
                    'animations' => 'staggered',
                ],
            ],
            [
                'name' => 'Clean Light',
                'slug' => 'clean-light',
                'description' => 'Tema claro e minimalista com fundo branco.',
                'is_default' => false,
                'config' => [
                    'background' => '#ffffff',
                    'backgroundType' => 'solid',
                    'grain' => false,
                    'cardBg' => 'rgba(0,0,0,0.03)',
                    'cardBorder' => 'rgba(0,0,0,0.08)',
                    'cardBlur' => false,
                    'textPrimary' => '#1a1a2e',
                    'textSecondary' => '#6b7280',
                    'nameGradient' => ['#1a1a2e', '#1a1a2e'],
                    'avatarRingGradient' => ['#6366f1', '#8b5cf6'],
                    'statusIndicator' => true,
                    'fontFamily' => 'sans-serif',
                    'animations' => 'staggered',
                ],
            ],
            [
                'name' => 'Ocean Dark',
                'slug' => 'ocean-dark',
                'description' => 'Tema escuro com tons de azul profundo e acentos teal.',
                'is_default' => false,
                'config' => [
                    'background' => '#0c1222',
                    'backgroundType' => 'gradient',
                    'gradientColors' => ['#1e3a5f', '#0c1222'],
                    'grain' => false,
                    'cardBg' => 'rgba(45,212,191,0.05)',
                    'cardBorder' => 'rgba(45,212,191,0.15)',
                    'cardBlur' => true,
                    'textPrimary' => '#e2e8f0',
                    'textSecondary' => '#94a3b8',
                    'nameGradient' => ['#e2e8f0', '#2dd4bf'],
                    'avatarRingGradient' => ['#2dd4bf', '#1e3a5f'],
                    'statusIndicator' => true,
                    'fontFamily' => 'Inter',
                    'animations' => 'staggered',
                ],
            ],
            [
                'name' => 'Sunset Warm',
                'slug' => 'sunset-warm',
                'description' => 'Tema escuro quente com tons de laranja e rosa.',
                'is_default' => false,
                'config' => [
                    'background' => '#1a0a0a',
                    'backgroundType' => 'mesh',
                    'meshColors' => ['#ff6b35', '#f7931e', '#ff1493'],
                    'grain' => true,
                    'cardBg' => 'rgba(255,107,53,0.05)',
                    'cardBorder' => 'rgba(255,107,53,0.15)',
                    'cardBlur' => true,
                    'textPrimary' => '#fff5f0',
                    'textSecondary' => '#d4a594',
                    'nameGradient' => ['#fff5f0', '#ff6b35'],
                    'avatarRingGradient' => ['#ff6b35', '#f7931e', '#ff1493'],
                    'statusIndicator' => true,
                    'fontFamily' => 'Inter',
                    'animations' => 'staggered',
                ],
            ],
            [
                'name' => 'Neon Cyber',
                'slug' => 'neon-cyber',
                'description' => 'Tema preto com acentos neon verde, ciano e magenta.',
                'is_default' => false,
                'config' => [
                    'background' => '#000000',
                    'backgroundType' => 'mesh',
                    'meshColors' => ['#00ff41', '#00d4ff', '#ff00ff'],
                    'grain' => true,
                    'cardBg' => 'rgba(0,255,65,0.03)',
                    'cardBorder' => 'rgba(0,255,65,0.15)',
                    'cardBlur' => true,
                    'textPrimary' => '#00ff41',
                    'textSecondary' => '#00d4ff',
                    'nameGradient' => ['#00ff41', '#00d4ff'],
                    'avatarRingGradient' => ['#00ff41', '#00d4ff', '#ff00ff'],
                    'statusIndicator' => true,
                    'fontFamily' => 'Inter',
                    'animations' => 'staggered',
                ],
            ],
        ];

        foreach ($themes as $theme) {
            Theme::updateOrCreate(
                ['slug' => $theme['slug']],
                $theme,
            );
        }
    }
}
