<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait BacksUpImages
{
    public static function bootBacksUpImages(): void
    {
        static::saved(function ($model) {
            foreach ($model->imageBackupFields ?? [] as $field) {
                $path = $model->$field;
                if (!$path) continue;

                $src  = Storage::disk('public')->path($path);
                $dest = storage_path("app/image_backups/{$path}");

                if (!file_exists($src)) continue;
                if (file_exists($dest)) continue;

                $dir = dirname($dest);
                if (!is_dir($dir)) {
                    mkdir($dir, 0755, true);
                }

                copy($src, $dest);
            }
        });
    }
}
