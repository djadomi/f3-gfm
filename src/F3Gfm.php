<?php

namespace Djadomi;

use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\MarkdownConverter;

class F3Gfm extends \Prefab {
	protected $converter;

	public function __construct() {
		$this->initConverter();
	}

    protected function initConverter(): void {
        $fw = \Base::instance();
        $htmlInput = $fw->get('GFM_HTML_INPUT') ?: 'escape';
        $unsafeLinks = (bool) ($fw->get('GFM_UNSAFE_LINKS') ?: false);
        $maxNesting = (int) ($fw->get('GFM_MAX_NESTING') ?: 100);
        $tableWrap = (bool) ($fw->get('GFM_TABLE_WRAP') ?: false);
        $config = [
            'html_input' => $htmlInput,
            'allow_unsafe_links' => $unsafeLinks,
            'max_nesting_level' => $maxNesting,
            'table' => [
                'wrap' => [
                    'enabled' => $tableWrap,
                    'attributes' => ['class' => 'gfm-table'],
                ],
            ],
        ];
        $environment = new Environment($config);
        $environment->addExtension(new CommonMarkCoreExtension());
        $environment->addExtension(new GithubFlavoredMarkdownExtension());
        $environment->addExtension(new AlertExtension());
        $this->converter = new MarkdownConverter($environment);
    }

	public function convert(string $markdown): string {
		return $this->converter->convert($markdown)->getContent();
	}

	public function render(string $markdown): string {
		return $this->convert($markdown);
	}
}
