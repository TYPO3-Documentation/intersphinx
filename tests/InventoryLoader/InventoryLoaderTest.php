<?php

declare(strict_types=1);

namespace T3Docs\Tests\Intersphinx\InventoryLoader;

use PHPUnit\Framework\TestCase;
use T3Docs\Intersphinx\Service\InventoryLoader;

use function count;
use function file_get_contents;
use function PHPUnit\Framework\assertIsString;

final class InventoryLoaderTest extends TestCase
{
    private InventoryLoader $inventoryLoader;

    protected function setUp(): void
    {
        $this->inventoryLoader = new InventoryLoader();
    }

    public function testInventoryLoaderLoadsInventory(): void
    {
        $jsonString = file_get_contents(__DIR__ . '/input/objects.inv.json');
        assertIsString($jsonString);
        $this->inventoryLoader->loadInventoryFromString('somekey', $jsonString);
        $inventory = $this->inventoryLoader->getInventoryRepository()->getInventory('somekey');
        self::assertGreaterThan(1, count($inventory->getGroups()));
    }

    public function testInventoryTitleGetsHtmlspecialChared(): void
    {
        $jsonString = file_get_contents(__DIR__ . '/input/objects.inv.json');
        assertIsString($jsonString);
        $this->inventoryLoader->loadInventoryFromString('somekey', $jsonString);
        $inventory = $this->inventoryLoader->getInventoryRepository()->getInventory('somekey');
        $link      = $inventory->getLink('std:doc', 'Index');
        self::assertEquals($link->getTitle(), '&lt;project&gt;');
    }
}
