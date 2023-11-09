<?php

namespace App\Console\Commands\Nova;

use App\Models\Release;
use App\Services\Nova;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Process\Exceptions\ProcessTimedOutException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;

class UpdateReleases extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:nova:update-releases';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Nova releases an store in app.';

    /**
     * Execute the console command.
     *
     * @throws \Exception
     */
    public function handle(): void
    {
        $releases = Nova::getReleases();

        $releases->each(function (array $release) {
            $this->handleRelease($release);
        });
        Storage::disk('nova')->deleteDirectory('temp/vendor');
    }

    /**
     * Handle Laravel Nova release.
     *
     * @param array{id: string, series_id: int, version: string, notes: string, created_at: string}  $release
     *
     * @throws \Exception
     * @return void
     */
    public function handleRelease(array $release): void
    {
        if (Release::whereVersion($release['version'])->exists()) {
            return;
        }

        $composerJson = [
            'repositories' => [
                [
                    'type' => 'composer',
                    'url' => 'https://nova.laravel.com',
                ],
            ],
            'require' => [
                'laravel/nova' => $release['version'],
            ],
        ];

        Storage::disk('nova')->put(
            'temp/composer.json',
            json_encode($composerJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
        );

        $command = [
            config('services.composer.command', 'composer'),
            'update',
            '--ignore-platform-reqs',
        ];

        try {
            Process::timeout(120)->path(Storage::disk('nova')->path('temp'))
                ->run(implode(' ', $command));
        } catch (ProcessTimedOutException $exception) {
            $this->error('Timeout [' . $release['version'] . ']: ' . $exception->getMessage());
            sleep(60);
            $this->info('Retry');
            $this->handleRelease($release);

            return;
        } catch (Exception $exception) {
            $this->error($exception->getMessage());

            throw $exception;
        }
        Storage::disk('nova')->move('temp/vendor/laravel/nova', 'releases/' . $release['version']);

        Release::create([
            'version' => $release['version'],
            'notes' => $release['notes'],
            'files' => $this->getFilesChecksumArray(
                Storage::disk('nova')->allFiles('releases/' . $release['version'])
            ),
            'published_at' => $release['created_at'],
        ]);
    }

    /**
     * @param array  $files
     *
     * @return array
     */
    protected function getFilesChecksumArray(array $files): array
    {
        $files = array_filter(
            $files,
            function ($file) {
                $ext = pathinfo(Storage::disk('nova')->path($file), PATHINFO_EXTENSION);
                $parts = explode('/', $file);

                return $parts[2] != 'public' && !in_array($ext, ['woff2', 'woff', 'ttf']);
            }
        );

        return Arr::mapWithKeys($files, function (string $file) {
            $key = explode('/', $file, 3)[2];

            return [$key => Storage::disk('nova')->checksum($file)];
        });
    }
}
