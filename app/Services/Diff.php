<?php

namespace App\Services;

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
     * Get the parsed difference between two strings.
     *
     * @throws \League\CommonMark\Exception\CommonMarkException
     * @return array
     */
    public function getParsed(): array
    {
        $output = [
            'oldLN' => [],
            'newLN' => [],
            'code' => [],
        ];

        $lines = explode("\n", trim($this->getUnified()));
        $oldLN = $newLN = null;
        foreach ($lines as $line) {
            $ident = $line[0];
            switch ($ident) {
                case '@':
                    $line = trim(preg_replace('/[^0-9, ]/', '', $line));
                    $numbers = explode(' ', $line);
                    $oldLN = explode(',', $numbers[0])[0];
                    $newLN = explode(',', $numbers[1])[0];
                    break;
                case '+':
                    $tlc = in_array($this->language, ['vue', 'html', 'blade']) ?
                        '<!-- [tl! add] -->' : '// [tl! add]';
                    $output['oldLN'][] = '';
                    $output['newLN'][] = $newLN++;
                    $output['code'][] = substr($line, 1) . '  ' . $tlc;
                    break;
                case '-':
                    $tlc = in_array($this->language, ['vue', 'html', 'blade']) ?
                        '<!-- [tl! remove] -->' : '// [tl! remove]';
                    $output['oldLN'][] = $oldLN++;
                    $output['newLN'][] = '';
                    $output['code'][] = substr($line, 1) . '  ' . $tlc;
                    break;
                default:
                    $output['oldLN'][] = $oldLN++;
                    $output['newLN'][] = $newLN++;
                    $output['code'][] = substr($line, 1);
            }
        }

        return [
            'oldLN' => implode('<br>', $output['oldLN']),
            'newLN' => implode('<br>', $output['newLN']),
            'code' => $this->renderCode($output['code']),
        ];
        //return array_map(fn ($item) => implode("\n", $item), $output);
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

        return (new MarkdownConverter())->convert($content)->getContent();
    }

    /**
     * Get the difference between 2 strings in "unified format" (unidiff).
     *
     * @see https://en.wikipedia.org/wiki/Diff#Unified_format
     *
     * @return string
     */
    protected function getUnified(): string
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
