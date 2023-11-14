<?php

namespace App\Notifications;

use App\Enums\AlertProviderEnum;
use App\Mail\NewReleaseMailable;
use App\Models\Alert;
use App\Models\Release;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class NewReleaseNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The Release instance.
     *
     * @var \App\Models\Release
     */
    protected Release $release;

    /**
     * Modified files compared to the previous version.
     *
     * @var array
     */
    protected array $fileModifications;

    /**
     * @var string
     */
    protected string $compareRoute;

    /**
     * @var string
     */
    protected string $previousVersion;

    /**
     * Create a new notification instance.
     */
    public function __construct(Release $release, array $fileModifications, string $compareRoute, string $previousVersion)
    {
        $this->release = $release;
        $this->fileModifications = $fileModifications;
        $this->compareRoute = $compareRoute;
        $this->previousVersion = $previousVersion;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(Alert $notifiable): array
    {
        return match ($notifiable->provider) {
            AlertProviderEnum::WEBHOOK => ['webhook'], // Todo
            AlertProviderEnum::TELEGRAM => ['telegram'], // Todo
            AlertProviderEnum::MS_TEAMS => ['teams'], // Todo
            AlertProviderEnum::SLACK => ['slack'], // Todo
            AlertProviderEnum::DISCORD => ['discord'], // Todo
            default => ['mail'],
        };
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(Alert $notifiable): NewReleaseMailable
    {
        $user = $notifiable->user;

        return (new NewReleaseMailable())
            ->subject(sprintf('Laravel Nova %s released', $this->release->version))
            ->to($user->email, $user->name)
            ->markdown('emails.new-release', [
                'comparison' => array_map(fn (array $value) => count($value), $this->fileModifications),
                'version' => $this->release->version,
                'notes' => $this->release->notes,
                'previousVersion' => $this->previousVersion,
                'url' => $this->compareRoute,
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(Alert $notifiable): array
    {
        return [
            //
        ];
    }
}
