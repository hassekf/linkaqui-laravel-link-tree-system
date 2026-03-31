<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class ImageService
{
    private ImageManager $manager;

    public function __construct()
    {
        $this->manager = new ImageManager(new Driver);
    }

    /**
     * Store an optimized image.
     *
     * @param  array{width: int, height: int}|null  $maxDimensions
     * @return string The stored file path
     */
    public function store(
        UploadedFile $file,
        string $directory,
        int $maxWidth = 800,
        int $maxHeight = 800,
        int $quality = 80,
    ): string {
        try {
            $image = $this->manager->read($file->getRealPath());

            $image->scaleDown(width: $maxWidth, height: $maxHeight);

            $encoded = $image->toWebp($quality);

            $filename = uniqid().'_'.time().'.webp';
            $path = $directory.'/'.$filename;

            Storage::disk('public')->put($path, (string) $encoded);

            return $path;
        } catch (\Throwable) {
            return $file->store($directory, 'public');
        }
    }

    /**
     * Store an avatar (square crop + resize).
     */
    public function storeAvatar(UploadedFile $file): string
    {
        try {
            $image = $this->manager->read($file->getRealPath());

            $image->cover(400, 400);

            $encoded = $image->toWebp(85);

            $filename = uniqid().'_'.time().'.webp';
            $path = 'avatars/'.$filename;

            Storage::disk('public')->put($path, (string) $encoded);

            return $path;
        } catch (\Throwable) {
            return $file->store('avatars', 'public');
        }
    }

    /**
     * Store a product image (resize maintaining aspect ratio).
     */
    public function storeProductImage(UploadedFile $file): string
    {
        return $this->store($file, 'products', 600, 600, 80);
    }

    /**
     * Store a link image (small, icon-like).
     */
    public function storeLinkImage(UploadedFile $file): string
    {
        return $this->store($file, 'links', 200, 200, 80);
    }
}
