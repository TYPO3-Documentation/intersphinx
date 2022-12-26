<?php

declare(strict_types=1);

namespace T3Docs\Tests\Intersphinx\InventoryLoader;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use T3Docs\Intersphinx\Service\InventoryLoader;
use T3Docs\Intersphinx\Service\JsonLoader;

use function count;
use function file_get_contents;
use function json_decode;
use function PHPUnit\Framework\assertIsString;

use const JSON_THROW_ON_ERROR;

final class InventoryLoaderTest extends TestCase
{
    private InventoryLoader $inventoryLoader;
    /** @var JsonLoader|MockObject */
    private $jsonLoader;
    /** @var array<string, mixed> */
    private array $json;

    protected function setUp(): void
    {
        $this->jsonLoader      = $this->createMock(JsonLoader::class);
        $this->inventoryLoader = new InventoryLoader();
        $jsonString            = file_get_contents(__DIR__ . '/input/objects.inv.json');
        assertIsString($jsonString);
        $this->json = json_decode($jsonString, true, 512, JSON_THROW_ON_ERROR);
        $this->inventoryLoader->loadInventoryFromJson('somekey', 'https://example.com/', $this->json);
    }

    public function testInventoryLoaderLoadsInventory(): void
    {
        $inventory = $this->inventoryLoader->getInventoryRepository()->getInventory('somekey');
        self::assertGreaterThan(1, count($inventory->getGroups()));
    }

    public function testInventoryTitleGetsHtmlspecialChared(): void
    {
        $inventory = $this->inventoryLoader->getInventoryRepository()->getInventory('somekey');
        self::assertEquals('&lt;project&gt;', $inventory->getLink('std:doc', 'Index')->getTitle());
    }

    public function testLoadInventoryFromUrl(): void
    {
        $inventoryLoader = new InventoryLoader(null, $this->jsonLoader);
        $this->jsonLoader->expects(self::atLeastOnce())->method('')->willReturn($this->json);
        $inventoryLoader->loadInventoryFromUrl('somekey', 'https://example.com/');
        $inventory = $inventoryLoader->getInventoryRepository()->getInventory('somekey');
        self::assertGreaterThan(1, count($inventory->getGroups()));
    }
}
