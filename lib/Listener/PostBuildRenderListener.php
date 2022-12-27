<?php

declare(strict_types=1);

namespace T3Docs\Intersphinx\Listener;

use Doctrine\RST\Event\PostBuildRenderEvent;
use T3Docs\Intersphinx\Service\InventoryWriter;
use T3Docs\Intersphinx\Service\JsonWriter;

final class PostBuildRenderListener
{
    private InventoryWriter $inventoryWriter;

    public function __construct(?InventoryWriter $inventoryWriter = null)
    {
        $this->inventoryWriter = $inventoryWriter ?? (new InventoryWriter);
    }

    public function postBuildRender(PostBuildRenderEvent $event)
    {
        $metas = $event->getBuilder()->getMetas();
        $filename = $event->getTargetDirectory() . '/objects.inv.json';
        $this->inventoryWriter->saveMetasToJson($metas, $filename);

    }
}