<?php

declare(strict_types=1);

namespace T3Docs\Tests\Intersphinx;

use Doctrine\RST\Meta\Metas;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use T3Docs\Intersphinx\Service\InventoryWriter;
use T3Docs\Intersphinx\Service\JsonWriter;

class InventoryWriterTest extends TestCase
{
    /** @var JsonWriter|MockObject */
    private $jsonWriter;

    private InventoryWriter $inventoryWriter;

    protected function setUp(): void
    {
        $this->jsonWriter      = $this->createMock(JsonWriter::class);
        $this->inventoryWriter = new InventoryWriter($this->jsonWriter);
    }

    public function testInventoryWriterWritesFile(): void
    {
        $metas = $this->createMock(Metas::class);
        $this->jsonWriter->expects(self::atLeastOnce())->method('saveJsonToFile');
        $this->inventoryWriter->saveMetasToJson($metas, 'objects.inv.json');
    }
}
