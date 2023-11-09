<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Benchmark;

class HomeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        Benchmark::dd(fn () => \App\Services\Nova::comparison('4.29.5', '4.27.14'), iterations: 500);

        return Inertia::render('Home/Index', [
            'ip' => $request->ip(),
        ]);
    }
}
