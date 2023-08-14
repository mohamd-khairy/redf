<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UploadService
{

    /**
     * @param $files
     * @param string $path
     * @param string $disk
     * @return array|false|mixed|string
     */
    public static function store($files, string $path = 'files', string $disk = 'public')
    {
        $items = is_array($files) ? $files : [$files];

        $paths = [];
        if (!empty($items)) {
            foreach ($items as $name => $item) {

                if (is_string($item)) {

                    $file = UploadService::generateUniqueFileName($item);

                    if (Storage::disk($disk)->put($file, base64_decode($item))) {
                        $paths[] = $path . '/' . $file;
                    }
                } else {
                    $paths[] = Storage::disk($disk)->putFile($path, $item);
                }
            }
        }

        return $paths ? (count($paths) == 1 ? $paths[0] : $paths) : null;
    }

    /**
     * @param $files
     * @param string $disk
     * @return bool
     */
    public static function delete($files, string $disk = 'public'): bool
    {
        $items = is_array($files) ? $files : [$files];

        if (!empty($items)) {
            foreach ($items as $item) {
                Storage::disk($disk)->delete($item);
            }
        }

        return true;
    }

    public static function generateUniqueFileName($originalFileName)
    {
        // && is_base64($item)
        $extension = explode('/', mime_content_type($originalFileName))[1];

        if ($extension === 'vnd.openxmlformats-officedocument.spreadsheetml.sheet') {
            $extension = 'xlsx';
        } elseif ($extension === 'octet-stream' || $extension === 'vnd.openxmlformats-officedocument.wordprocessingml.document') {
            $extension = 'docx';
        } elseif ($extension === 'plain') {
            $extension = 'txt';
        } else {
            $extension = explode('/', mime_content_type($originalFileName))[1];
        }

        return uniqid() . '_' . Str::random(8) . '.' . $extension;
    }
}
