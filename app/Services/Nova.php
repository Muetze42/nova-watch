<?php

namespace App\Services;

use App\Models\Release;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class Nova
{
    /**
     * Check to see if Nova is valid for the license key.
     *
     * @param string  $host
     * @param string  $key
     *
     * @return array|mixed|null
     */
    public static function getNovaLicenceValidationError(string $host, string $key): mixed
    {
        $response = Http::post('https://nova.laravel.com/api/license-check', [
            'url' => $host,
            'key' => $key,
        ]);

        if ($response->status() == 204) {
            return null;
        }

        return $response->json('message');
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

    /**
     * Compare the files of 2 Releases.
     *
     * @param string  $version1
     * @param string  $version2
     *
     * @return array|null
     */
    public static function comparison(string $version1, string $version2): ?array
    {
        $versions = [$version1, $version2];
        sort($versions);
        $file = 'comparisons/' . implode('-', $versions);
        if (Storage::disk('nova')->exists($file)) {
            return Storage::disk('nova')->json($file);
        }

        $files = Release::whereMajorVersion(getMajorVersion($version1))
            ->where(function (Builder $query) use ($version1, $version2) {
                /* @var \Illuminate\Database\Eloquent\Builder|\App\Models\Release $query */
                $query->where('version', $version1)->orWhere('version', $version2);
            })
            ->orderBy('version_id')
            ->get('files')
            ->pluck('files')
            ->toArray();

        if (count($files) != 2) {
            return null;
        }

        $updated = array_unique(array_merge(
            array_keys(array_diff($files[0], $files[1])),
            array_keys(array_diff($files[1], $files[0])),
        ));

        $releaseFiles = [
            Arr::where(array_keys($files[0]), function (string $file) use ($updated) {
                return in_array($file, $updated);
            }),
            Arr::where(array_keys($files[1]), function (string $file) use ($updated) {
                return in_array($file, $updated);
            }),
        ];

        $created = array_diff($releaseFiles[1], $releaseFiles[0]);
        $deleted = array_diff($releaseFiles[0], $releaseFiles[1]);

        $updated = Arr::where($updated, function (string $file) use ($created, $deleted) {
            return !in_array($file, $created) && !in_array($file, $deleted);
        });

        $data = [
            'created' => $created,
            'deleted' => $deleted,
            'updated' => $updated,
        ];

        Storage::disk('nova')->put($file, json_encode($data));

        return $data;
    }
}
