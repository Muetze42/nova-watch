<?php

namespace App\Http\Controllers;

use App\Enums\NotificationProviderEnum;
use App\Models\Notification;
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
    public function update(Request $request, string $slug)
    {
        $resource = $this->getResource($slug);

        $resource = new $resource();
        $fields = $resource->getFields();

        $validate = [
            'resource.scopes' => ['string', 'nullable'],
            'resource.active' => ['required', 'bool'],
        ];
        $requirements = $fields->where('required', true)->pluck('column');
        foreach ($requirements as $requirement) {
            $validate['resource.config.' . $requirement] = ['required'];
        }

        $request->validate($validate);

        $request
            ->user()
            ->notifications()
            ->updateOrCreate(
                ['provider' => $resource->getProvider()],
                [
                    'active' => $request->boolean('resource.active'),
                    'scopes' => $request->input('resource.scopes'),
                    'config' => $request->input('resource.config'),
                ]
            );

        return true;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Notification $notification)
    {
        // Todo
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, string $slug)
    {
        $resource = $this->getResource($slug);

        $resource = new $resource();
        $provider = $resource->getProvider();
        $fields = $resource->getFields();

        /* @var \App\Models\Notification|null $notification */
        $notification = $request
            ->user()
            ->notifications()
            ->where('provider', $provider)
            ->first();

        $notifyConfig = $notification ? $notification->config : [];

        return Inertia::render('Notification/Edit', [
            'active' => (bool) $notification?->active,
            'slug' => $slug,
            'fields' => $fields,
            'scopes' => $notification?->scopes,
            'label' => $provider->label(),
            'config' => $fields->pluck('column')
                ->mapWithKeys(function (string $column) use ($notifyConfig) {
                    return [$column => data_get($notifyConfig, $column)];
                }),
        ]);
    }

    /**
     * @param string $slug
     * @return \App\Users\Resources\Notifications\AbstractResource|mixed|string
     */
    protected function getResource(string $slug): mixed
    {
        $namespace = app()->getNamespace();
        $resource = $namespace . 'Users\\Resources\\Notifications\\' . Str::studly($slug);

        if (!class_exists($resource) && !$resource instanceof AbstractResource) {
            abort(404);
        }

        return $resource;
    }
}
