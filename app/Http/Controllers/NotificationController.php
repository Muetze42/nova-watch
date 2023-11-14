<?php

namespace App\Http\Controllers;

use App\Enums\NotificationProviderEnum;
use App\Users\Resources\Notifications\AbstractResource;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        $resource = 'App\\Users\\Resources\\Notifications\\' . Str::studly($slug);

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
            'active' => (bool)$notification?->active,
            'fields' => $resource->getFields(),
            'scopes' => $notification?->scopes,
            'label' => $provider->label(),
        ]);
    }
}
