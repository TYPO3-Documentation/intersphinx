<?php

declare(strict_types=1);

namespace T3Docs\Tests\Intersphinx;

use Doctrine\RST\Builder;
use Doctrine\RST\Event\PostBuildRenderEvent;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use T3Docs\Intersphinx\Listener\PostBuildRenderListener;
use T3Docs\Intersphinx\Service\InventoryWriter;

class PostBuildRenderListenerTest extends TestCase
{
    private PostBuildRenderListener $listener;
    /** @var InventoryWriter|MockObject */
    private $inventoryWriter;

    /** @var Builder|MockObject */
    private $builder;

    protected function setUp(): void
    {
        $this->inventoryWriter = $this->createMock(InventoryWriter::class);
        $this->builder         = $this->createMock(Builder::class);
        $this->listener        = new PostBuildRenderListener($this->inventoryWriter);
    }

    public function testDataWitOneColonSetsResolvedReference(): void
    {
        $event = new PostBuildRenderEvent(
            $this->builder,
            'input',
            'output'
        );
        $this->inventoryWriter->expects(self::atLeastOnce())->method('saveMetasToJson');
        $this->listener->postBuildRender($event);
    }
}
