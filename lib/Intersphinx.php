<?php

declare(strict_types=1);

namespace T3Docs\Intersphinx;

use Doctrine\RST\Configuration;
use Doctrine\RST\ErrorManager;
use Doctrine\RST\Event\MissingReferenceResolverEvent;
use Doctrine\RST\Event\PostBuildRenderEvent;
use T3Docs\Intersphinx\Listener\MissingReferenceResolverListener;
use T3Docs\Intersphinx\Listener\PostBuildRenderListener;
use T3Docs\Intersphinx\Repository\InventoryRepository;
use T3Docs\Intersphinx\Service\InventoryLoader;
use T3Docs\Intersphinx\Service\LinkResolver;

class Intersphinx
{
    private Configuration $configuration;
    private ErrorManager $errorManager;
    private InventoryRepository $inventoryRepository;

    public static function getIntersphinxFromInventoryRepository(Configuration $configuration, InventoryRepository $inventoryRepository): Intersphinx
    {
        return new Intersphinx($configuration, $inventoryRepository);
    }

    /** @param array<String, String> $urls */
    public static function getIntersphinxFromUrlArray(Configuration $configuration, array $urls): Intersphinx
    {
        $inventoryLoader = new InventoryLoader();
        foreach ($urls as $key => $url) {
            $inventoryLoader->loadInventoryFromUrl($key, $url);
        }

        return new Intersphinx($configuration, $inventoryLoader->getInventoryRepository());
    }

    public function __construct(Configuration $configuration, InventoryRepository $inventoryRepository)
    {
        $this->configuration = $configuration;
        $this->errorManager  = new ErrorManager($this->configuration);
        $eventManager        = $this->configuration->getEventManager();
        $eventManager->addEventListener(
            [MissingReferenceResolverEvent::MISSING_REFERENCE_RESOLVER],
            new MissingReferenceResolverListener(new LinkResolver($inventoryRepository, $this->errorManager))
        );
        $eventManager->addEventListener(
            [PostBuildRenderEvent::POST_BUILD_RENDER],
            new PostBuildRenderListener()
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
