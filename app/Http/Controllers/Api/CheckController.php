<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Release;

class CheckController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(string $version)
    {
        $releases = Release::where('version_id', '>', getVersionId($version))
            ->orderBy('version_id')
            ->get(['version', 'notes']);

        if (!$releases->count()) {
            return response(null, 201);
        }

        return [
            'current' => $releases->last()->version,
            'compare' => route('compare', [parseVersion($version), $releases->last()->version]),
            'notes' => $releases->pluck('notes', 'version'),
        ];
    }
}
