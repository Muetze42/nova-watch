<?php

namespace App\Services;

use Jfcherng\Diff\Differ;
use Jfcherng\Diff\Factory\RendererFactory;

class Diff
{
    /**
     * Contents of the old string to compare.
     *
     * @var string
     */
    protected string $oldContents;

    /**
     * Contents of the new string to compare.
     *
     * @var string
     */
    protected string $newContents;

    public function __construct(string $oldContents, string $newContents, string $filename)
    {
        $this->oldContents = $oldContents;
        $this->newContents = $newContents;
    }

    /**
     * Get the difference between 2 strings in "unified format" (unidiff).
     *
     * @see https://en.wikipedia.org/wiki/Diff#Unified_format
     *
     * @return string
     */
    public function getUnified(): string
    {
        $rendererName = 'Unified';
        $differOptions = [
            'context' => 3,
            'ignoreCase' => false,
            'ignoreLineEnding' => false,
            'ignoreWhitespace' => false,
            'lengthLimit' => 5000,
        ];

        $differ = new Differ(
            explode("\n", $this->oldContents),
            explode("\n", $this->newContents),
            $differOptions
        );

        $renderer = RendererFactory::make($rendererName); // or your own renderer object

        return $renderer->render($differ);
    }
}
