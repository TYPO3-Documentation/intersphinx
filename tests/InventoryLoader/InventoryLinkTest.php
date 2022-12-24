<?php

declare(strict_types=1);

namespace T3Docs\Tests\Intersphinx\InventoryLoader;

use PHPUnit\Framework\TestCase;
use RuntimeException;
use T3Docs\Intersphinx\Model\InventoryLink;

final class InventoryLinkTest extends TestCase
{
    public function testHtmlLinkSet(): void
    {
        $link          = 'SomeThing.html';
        $inventoryLink = new InventoryLink('', '', $link, '');
        self::assertEquals($inventoryLink->getPath(), $link);
    }

    public function testHtmlLinkWithPathSet(): void
    {
        $link          = 'Some/Path/SomeThing.html';
        $inventoryLink = new InventoryLink('', '', $link, '');
        self::assertEquals($inventoryLink->getPath(), $link);
    }

    public function testHtmlLinkWithPathAndAnchorSet(): void
    {
        $link          = 'Some/Path/SomeThing.html#anchor';
        $inventoryLink = new InventoryLink('', '', $link, '');
        self::assertEquals($inventoryLink->getPath(), $link);
    }

    public function testPhpLinkThrowsError(): void
    {
        $link = 'Some/Path/SomeThing.php#anchor';
        $this->expectException(RuntimeException::class);
        new InventoryLink('', '', $link, '');
    }

    public function testJavaScriptLinkThrowsError(): void
    {
        $link = 'javascript:alert()';
        $this->expectException(RuntimeException::class);
        new InventoryLink('', '', $link, '');
    }

    public function testUrlLinkThrowsError(): void
    {
        $link = 'https://example.com';
        $this->expectException(RuntimeException::class);
        new InventoryLink('', '', $link, '');
    }
}
