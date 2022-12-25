<?php

declare(strict_types=1);

namespace T3Docs\Intersphinx;

use Doctrine\RST\Configuration;
use Doctrine\RST\ErrorManager;
use Doctrine\RST\Event\MissingReferenceResolverEvent;
use T3Docs\Intersphinx\Listener\MissingReferenceResolverListener;
use T3Docs\Intersphinx\Repository\InventoryRepository;

class Intersphinx
{
    private Configuration $configuration;
    private ErrorManager $errorManager;
    private InventoryRepository $inventoryRepository;

    public function __construct(Configuration $configuration, InventoryRepository $inventoryRepository)
    {
        $this->configuration = $configuration;
        $this->errorManager  = new ErrorManager($this->configuration);
        $eventManager        = $this->configuration->getEventManager();
        $eventManager->addEventListener(
            [MissingReferenceResolverEvent::MISSING_REFERENCE_RESOLVER],
            new MissingReferenceResolverListener()
        );
        $this->inventoryRepository = $inventoryRepository;
    }

    public function getErrorManager(): ErrorManager
    {
        return $this->errorManager;
    }

    public function getInventoryRepository(): InventoryRepository
    {
        return $this->inventoryRepository;
    }
}
