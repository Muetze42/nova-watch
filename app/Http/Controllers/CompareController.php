<?php

namespace App\Http\Controllers;

use App\Models\Release;
use App\Services\Diff;
use App\Services\Markdown\NoteMarkdownConverter;
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

        //if (!empty($comparison['files']) && !$request->verifiedNovaLicence()) {
        //    $comparison['files'] = array_map('count', $comparison['files']);
        //}

        return Inertia::render('Compare', [
            'comparison' => $comparison,
        ]);
    }

    /**
     * Get updated file differences between two Laravel Nova versions.
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
        ))->getUnified();
    }

    /**
     * @param \Illuminate\Http\Request  $request
     *
     * @return array
     */
    public function notes(Request $request): array
    {
        $request->validate([
            'v1' => ['required', 'string'],
            'v2' => ['required', 'string'],
        ]);

        $versions = [
            getVersionId($request->input('v1')),
            getVersionId($request->input('v2')),
        ];
        sort($versions);

        return Release::where('version_id', '>=', $versions[0])
            ->where('version_id', '<=', $versions[1])
            ->orderByDesc('version_id')
            ->get(['version', 'notes', 'published_at'])
            ->mapWithKeys(fn (Release $release) => [
                $release->version => [
                    'published_at' => $release->published_at->toFormattedDateString(),
                    'notes' => str_replace(
                        "\n",
                        '',
                        (new NoteMarkdownConverter())->convert(trim($release->notes))->getContent()
                    ),
                ],
            ])
            ->toArray();
    }
}
