<?php

use GFM\GFM;
use PHPUnit\Framework\TestCase;

class GFMTest extends TestCase {
	protected function setUp(): void {
		require_once __DIR__ . '/../vendor/autoload.php';
	}

	public function testTable(): void {
		$markdown = "| Header 1 | Header 2 |\n|----------|----------|\n| Cell 1   | Cell 2   |";
		$gfm = GFM::instance();
		$html = $gfm->convert($markdown);
		$this->assertStringContainsString('<table>', $html);
		$this->assertStringContainsString('<th', $html);
		$this->assertStringContainsString('<td', $html);
	}

	public function testTaskList(): void {
		$markdown = "- [ ] Task 1\n- [x] Task 2";
		$gfm = GFM::instance();
		$html = $gfm->convert($markdown);
		$this->assertStringContainsString('type="checkbox"', $html);
		$this->assertStringContainsString('checked', $html);
	}

	public function testAutolink(): void {
		$markdown = "Visit https://example.com for more info.";
		$gfm = GFM::instance();
		$html = $gfm->convert($markdown);
		$this->assertStringContainsString('<a href=', $html);
		$this->assertStringContainsString('example.com', $html);
	}

	public function testStrikethrough(): void {
		$markdown = "~~deleted text~~";
		$gfm = GFM::instance();
		$html = $gfm->convert($markdown);
		$this->assertStringContainsString('<del>', $html);
	}

	public function testRenderAlias(): void {
		$markdown = "# Heading";
		$gfm = GFM::instance();
		$html = $gfm->render($markdown);
		$this->assertStringContainsString('<h1>', $html);
	}

	public function testSingletonPattern(): void {
		$instance1 = GFM::instance();
		$instance2 = GFM::instance();
		$this->assertSame($instance1, $instance2);
	}
}
