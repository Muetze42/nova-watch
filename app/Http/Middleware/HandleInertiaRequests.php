<?php

namespace App\Http\Middleware;

use App\Models\Release;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app.layout';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     *
     * @param \Illuminate\Http\Request  $request
     *
     * @return string|null
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @param \Illuminate\Http\Request  $request
     *
     * @return array
     */
    public function share(Request $request): array
    {
        $user = $request->user();
        $versions = Release::orderByDesc('version_id')
            ->get('version')
            ->pluck('version')
            ->toArray();
        $route = $request->route();

        return array_merge(parent::share($request), [
            'user' => fn () => $user ? $user->only(['name', 'email']) : ['name' => 'Norman', 'email' => 'as'],
            'licensed' => fn () => $request->verifiedNovaLicence(),
            'versions' => $versions,
            'selected' => [
                $this->getVersion($route, 'version1', $versions),
                $this->getVersion($route, 'version2', $versions),
            ],
        ]);
    }

    /**
     * Get valid versions option for the current request.
     *
     * @param \Illuminate\Routing\Route  $route
     * @param string                     $name
     * @param array                      $versions
     *
     * @return string
     */
    protected function getVersion(Route $route, string $name, array $versions): string
    {
        $version = $route->parameter($name);

        return in_array($version, $versions) ? $version : $versions[0];
    }
}
