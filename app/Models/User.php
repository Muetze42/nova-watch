<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'github_id',
        'save_licence',
        'licence_url',
        'licence_key',
        'licence_checked_at',
        'delete_request_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'remember_token',
        'licence_url',
        'licence_key',
        'licence_checked_at',
        'delete_request_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'save_licence' => 'bool',
        'licence_url' => 'encrypted',
        'licence_key' => 'encrypted',
        'licence_checked_at' => 'datetime',
        'delete_request_at' => 'datetime',
    ];

    /**
     * Get the notifications for the user.
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Determine if the user has verified a Laravel Nova licence.
     *
     * @return bool
     */
    public function hasVerifiedNovaLicence(): bool
    {
        return $this->licence_checked_at && $this->licence_checked_at > now()->subWeek();
    }
}
