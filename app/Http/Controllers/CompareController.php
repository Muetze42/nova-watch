<?php

namespace App\Http\Controllers;

use App\Services\Diff;
use App\Services\Nova;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class CompareController extends Controller
{
    /**
     * Compare 2 Laravel Nova versions.
     *
     * @param \Illuminate\Http\Request  $request
     * @param string|null               $version1
     * @param string|null               $version2
     *
     * @return \Inertia\Response
     */
    public function compare(Request $request, ?string $version1 = null, ?string $version2 = null)
    {
        $comparison = $version1 && $version2 && $version1 != $version2
            ? Nova::comparison($version1, $version2) : null;

        if ($comparison && !$request->verifiedNovaLicence()) {
            $comparison = array_map('count', $comparison);
        }

        return Inertia::render('Compare', ['comparison' => $comparison]);
    }

    /**
     * Get updated file differences between two Laravel Nova versions.
     *
     * @param \Illuminate\Http\Request  $request
     *
     * @throws \League\CommonMark\Exception\CommonMarkException
     * @return array
     */
    public function diff(Request $request)
    {
        if (!$request->verifiedNovaLicence()) {
            abort(401);
        }

        $request->validate([
            'file' => ['required', 'string'],
            'v1' => ['required', 'string'],
            'v2' => ['required', 'string'],
        ]);

        $versions = [$request->input('v1'), $request->input('v2')];
        usort($versions, 'version_compare');

        $file = $request->input('file');

        $oldFile = 'releases/' . $versions[0] . '/' . $file;
        $newFile = 'releases/' . $versions[1] . '/' . $file;

        return (new Diff(
            Storage::disk('nova')->exists($oldFile) ? Storage::disk('nova')->get($oldFile) : '',
            Storage::disk('nova')->exists($newFile) ? Storage::disk('nova')->get($newFile) : '',
            $file
        ))->getParsed();
    }

    /**
     * @throws \League\CommonMark\Exception\CommonMarkException
     */
    public function debug()
    {
        $oldFile = 'releases/4.0.6/composer.json';
        $newFile = 'releases/4.29.5/composer.json';

        return (new Diff(
            Storage::disk('nova')->exists($oldFile) ? Storage::disk('nova')->get($oldFile) : '',
            Storage::disk('nova')->exists($newFile) ? Storage::disk('nova')->get($newFile) : '',
            'composer.json'
        ))->getParsed()['code'];
    }
}
