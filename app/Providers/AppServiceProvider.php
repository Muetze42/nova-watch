<?php

namespace App\Providers;

use App\Services\ExtendedUrlGenerator;
use Illuminate\Http\Request;
use Illuminate\Routing\UrlGenerator;
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
        $this->extends();
    }

    /**
     * 'Extend' an abstract types in the container.
     *
     * @return void
     */
    public function extends(): void
    {
        $this->app->extend('url', function (UrlGenerator $urlGenerator) {
            return new ExtendedUrlGenerator(
                $this->app->make('router')->getRoutes(),
                $urlGenerator->getRequest(),
                $this->app->make('config')->get('app.asset_url')
            );
        });
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
