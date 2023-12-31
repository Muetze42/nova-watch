<?php

namespace App\Services;

use Illuminate\Routing\UrlGenerator;

class ExtendedUrlGenerator extends UrlGenerator
{
    /**
     * Generate the URL to an application asset.
     *
     * @param string     $path
     * @param bool|null  $secure
     *
     * @return string
     */
    public function asset($path, $secure = null): string
    {
        $file = public_path($path);
        if (!str_starts_with($path, 'build/') && file_exists($file)) {
            $path .= '?v=' . md5_file($file);
        }

        return parent::asset($path, $secure);
    }
}
