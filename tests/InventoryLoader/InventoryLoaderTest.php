<?php

declare(strict_types=1);

namespace T3Docs\Tests\Intersphinx\InventoryLoader;

use PHPUnit\Framework\TestCase;
use T3Docs\Intersphinx\Service\InventoryLoader;

use function file_get_contents;
use function PHPUnit\Framework\assertIsString;
use function sizeof;

final class InventoryLoaderTest extends TestCase
{
    private InventoryLoader $inventoryLoader;

    protected function setUp(): void
    {
        $this->inventoryLoader = InventoryLoader::getInventoryLoader();
    }

    public function testInventoryLoaderLoadsInventory(): void
    {
        $jsonString = file_get_contents(__DIR__ . '/input/objects.inv.json');
        assertIsString($jsonString);
        $this->inventoryLoader->loadInventoryFromString('somekey', $jsonString);
        $inventory = $this->inventoryLoader->getInventory('somekey');
        self::assertGreaterThan(1, sizeof($inventory->getGroups()));
    }

    public function testInventoryTitleGetsHtmlspecialChared(): void
    {
        $jsonString = file_get_contents(__DIR__ . '/input/objects.inv.json');
        assertIsString($jsonString);
        $this->inventoryLoader->loadInventoryFromString('somekey', $jsonString);
        $inventory = $this->inventoryLoader->getInventory('somekey');
        $link      = $inventory->getLink('std:doc', 'Index');
        self::assertEquals($link->getTitle(), '&lt;project&gt;');
    }
}
