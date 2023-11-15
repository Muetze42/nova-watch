<?php

namespace App\Http\Controllers;

use App\Enums\NotificationProviderEnum;
use App\Users\Resources\Notifications\AbstractResource;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use ReflectionClass;
use Symfony\Component\Finder\Finder;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @throws \ReflectionException
     */
    public function index(Request $request)
    {
        $resources = [];

        foreach ((new Finder())->in(app_path('Users/Resources'))->files() as $resource) {
            $resource = app()->getNamespace() . str_replace(
                ['/', '.php'],
                ['\\', ''],
                Str::after($resource->getPathname(), app_path() . DIRECTORY_SEPARATOR)
            );

            if (is_subclass_of($resource, AbstractResource::class) && !(new ReflectionClass($resource))->isAbstract()) {
                $resources[] = $resource;
            }
        }

        $active = $request->user()
            ->notifications()
            ->whereActive(true)
            ->pluck('provider')
            ->map(fn (NotificationProviderEnum $provider) => $provider->value)
            ->toArray();

        $resources = collect($resources)->map(function (string $resource) use ($active) {
            /* @var \App\Users\Resources\Notifications\AbstractResource $resource */
            $resource = new $resource();
            $provider = $resource->getProvider();

            return [
                'label' => $provider->label(),
                'active' => in_array($provider->value, $active),
                'path' => Str::kebab(class_basename($resource)),
            ];
        });

        return Inertia::render('Notification/Index', [
            'resources' => $resources,
        ]);
    }

    /**
     * Store a newly created resource or update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, string $slug)
    {
        $namespace = app()->getNamespace();
        $resource = $namespace . 'Users\\Resources\\Notifications\\' . Str::studly($slug);

        if (!class_exists($resource) && !$resource instanceof AbstractResource) {
            abort(404);
        }

        $resource = new $resource();
        $provider = $resource->getProvider();

        /* @var \App\Models\Notification|null $notification */
        $notification = $request
            ->user()
            ->notifications()
            ->where('provider', $provider)
            ->first();

        return Inertia::render('Notification/Edit', [
            'config' => $notification?->config,
            'active' => (bool) $notification?->active,
            'fields' => $resource->getFields(),
            'scopes' => $notification?->scopes,
            'label' => $provider->label(),
        ]);
    }
}
