<?php

namespace App\Services;

use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\Attributes\AttributesExtension;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\ExternalLink\ExternalLinkExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\MarkdownConverter as Converter;
use Torchlight\Commonmark\V2\TorchlightExtension;

class MarkdownConverter extends Converter
{
    /**
     * Create a new Markdown converter pre-configured for GFM
     * https://commonmark.thephpleague.com/2.4/extensions/overview/
     *
     * @param array<string, mixed>  $config
     */
    public function __construct(array $config = [])
    {
        $environment = new Environment();
        $environment->addExtension(new CommonMarkCoreExtension())
            ->addExtension(new TorchlightExtension())
            ->addExtension(new GithubFlavoredMarkdownExtension())
            ->addExtension(new AttributesExtension())
            ->addExtension(new ExternalLinkExtension());

        parent::__construct($environment);
    }
}
