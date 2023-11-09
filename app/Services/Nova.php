<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class Nova
{
    /**
     * Check to see if Nova is valid for the license key.
     *
     * @param string  $host
     * @param string  $key
     *
     * @return bool
     */
    public static function checkUserLicenseValidity(string $host, string $key): bool
    {
        $response = Http::post('https://nova.laravel.com/api/license-check', [
            'url' => $host,
            'key' => $key,
        ]);

        return $response->status() == 204;
    }

    /**
     * Get all Nova versions from the current major release.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function getReleases(): Collection
    {
        $contents = file_get_contents('https://nova.laravel.com/releases');
        $contents = explode('data-page="', $contents)[1];
        $contents = explode('"', $contents)[0];
        $contents = htmlspecialchars_decode($contents, ENT_QUOTES | ENT_SUBSTITUTE);
        $data = json_decode($contents, true);
        $releases = data_get($data, 'props.releases');

        if (!isset($releases[0]['version'])) {
            appLog($releases);
        }

        $majorVersion = explode(
            '.',
            parseVersion($releases[0]['version'])
        )[0];

        $releases = collect($releases)->map(function (array $release) {
            $release['version'] = parseVersion($release['version']);

            return $release;
        })->sortBy('version');

        return $releases->filter(function (array $release) use ($majorVersion) {
            $version = explode('.', $release['version'])[0];

            return $version == $majorVersion;
        });
    }
}
