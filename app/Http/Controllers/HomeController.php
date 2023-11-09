<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        dd(\App\Models\Release::all()->pluck('notes'));
        dd(array_diff(
            \App\Models\Release::first()->files,
            \App\Models\Release::latest()->first()->files,
        ));
        dd(\App\Models\Release::latest()->first()->files);

        return Inertia::render('Home/Index', [
            'ip' => $request->ip(),
        ]);
    }
}
