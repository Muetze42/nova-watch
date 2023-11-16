<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\Notification
 *
 * @property int $id
 * @property int $user_id
 * @property \App\Enums\NotificationProviderEnum $provider
 * @property bool $active
 * @property string|null $scopes
 * @property array|null $config
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\NotificationFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Notification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Notification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Notification query()
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereConfig($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereProvider($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereScopes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereUserId($value)
 */
	class Notification extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Release
 *
 * @property int $id
 * @property string $version
 * @property int $version_id
 * @property int $major_version
 * @property string $notes
 * @property array|null $files
 * @property \Illuminate\Support\Carbon|null $published_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\ReleaseFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Release newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Release newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Release query()
 * @method static \Illuminate\Database\Eloquent\Builder|Release whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Release whereFiles($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Release whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Release whereMajorVersion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Release whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Release wherePublishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Release whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Release whereVersion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Release whereVersionId($value)
 */
	class Release extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property int $github_id
 * @property string $name
 * @property string $email
 * @property string|null $remember_token
 * @property bool $save_licence
 * @property mixed|null $licence_url
 * @property mixed|null $licence_key
 * @property \Illuminate\Support\Carbon|null $licence_checked_at
 * @property \Illuminate\Support\Carbon|null $delete_request_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Notification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDeleteRequestAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereGithubId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLicenceCheckedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLicenceKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLicenceUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereSaveLicence($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

