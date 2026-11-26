<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

/**
 * Stores content photos on the public disk under random names.
 */
final class ImageStore
{
    private const string DISK = 'public';

    /**
     * Returns the path to persist: the new upload, null when removed, or the current one.
     */
    public function replace(?string $current, ?UploadedFile $upload, bool $remove, string $folder): ?string
    {
        if ($upload === null && ! $remove) {
            return $current;
        }
        $this->delete($current);
        if ($upload === null) {
            return null;
        }

        $path = $upload->store($folder, self::DISK);
        if ($path === false) {
            throw new RuntimeException('image upload could not be stored');
        }

        return $path;
    }

    public function delete(?string $path): void
    {
        if ($path !== null) {
            Storage::disk(self::DISK)->delete($path);
        }
    }

    public static function url(?string $path): ?string
    {
        return $path === null ? null : Storage::disk(self::DISK)->url($path);
    }
}
