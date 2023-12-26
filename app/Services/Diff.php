<?php

namespace App\Services;

use App\Services\Markdown\CodeMarkdownConverter;
use Illuminate\Support\Str;
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

    /**
     * The code language.
     *
     * @var string
     */
    protected string $language;

    public function __construct(string $oldContents, string $newContents, string $filename)
    {
        $this->oldContents = $oldContents;
        $this->newContents = $newContents;
        $this->determineLanguage($filename);
    }

    protected function determineLanguage(string $filename): void
    {
        $ext = Str::lower(pathinfo($filename, PATHINFO_EXTENSION));

        $this->language = match ($ext) {
            'cjs', 'eslintrc' => 'javascript',
            'env', 'example', 'gitignore' => 'shell',
            'eslintignore', 'prettierignore' => 'gitignore',
            'php' => str_ends_with($filename, '.blade.php') ? 'blade' : 'php',
            'yml' => 'yaml',
            default => $ext,
        };
    }

    /**
     * @param string|array  $code
     *
     * @throws \League\CommonMark\Exception\CommonMarkException
     * @return string
     */
    protected function renderCode(string|array $code): string
    {
        if (is_array($code)) {
            $code = implode("\n", $code);
        }

        $content = '```' . $this->language . "\n" . $code . "\n```";

        return (new CodeMarkdownConverter())->convert($content)->getContent();
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
