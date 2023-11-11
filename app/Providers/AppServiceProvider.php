<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->bootMacros();
    }

    /**
     * Bootstrap application macros.
     */
    public function bootMacros(): void
    {
        Request::macro('verifiedNovaLicence', function (): bool {
            if ($this->user()) {
                return $this->user()->hasVerifiedNovaLicence();
            }

            $session = $this->session();
            if ($session && $checked = $session->get('licence_checked_at')) {
                return $checked > now()->subDay()->timestamp;
            }

            return false;
        });
    }
}
