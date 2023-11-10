<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Services\Nova;

class CompareController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, ?string $version1 = null, ?string $version2 = null)
    {
        $comparison = $version1 && $version2 && $version1 != $version2
            ? Nova::comparison($version1, $version2) : null;

        if ($comparison && !$request->verifiedNovaLicence()) {
            $comparison = array_map('count', $comparison);
        }

        return Inertia::render('Compare', ['comparison' => $comparison]);
    }
}
