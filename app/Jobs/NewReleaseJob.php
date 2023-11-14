<?php

namespace App\Jobs;

use App\Models\Notification;
use App\Models\Release;
use App\Notifications\NewReleaseNotification;
use App\Services\Nova;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NewReleaseJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * The Release instance.
     *
     * @var \App\Models\Release
     */
    protected Release $release;

    /**
     * Create a new job instance.
     */
    public function __construct(Release $release)
    {
        $this->release = $release;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $previousVersion = Release::where('version_id', '<', $this->release->version_id)
            ->orderByDesc('version_id')
            ->first()
            ->version;

        $comparison = Nova::comparison($previousVersion, $this->release->version);
        $compareRoute = route('compare', [parseVersion($previousVersion), $this->release->version]);

        Notification::whereActive(true)->each(function (Notification $notification) use ($comparison, $compareRoute, $previousVersion) {
            $notification->notify(new NewReleaseNotification($this->release, $comparison['files'], $compareRoute, $previousVersion));
        });
    }

    /**
     * Get the unique ID for the job.
     */
    public function uniqueId(): string
    {
        return $this->release->version;
    }
}
