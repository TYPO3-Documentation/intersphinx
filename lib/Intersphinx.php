<?php

declare(strict_types=1);

namespace T3Docs\Intersphinx;

use Doctrine\RST\Configuration;
use Doctrine\RST\Event\PreReferenceResolvedEvent;
use T3Docs\Intersphinx\Listener\PreReferenceResolvedListener;

final class Intersphinx
{
    private Configuration $configuration;

    public function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;
        $eventManager        = $this->configuration->getEventManager();
        $eventManager->addEventListener(
            [PreReferenceResolvedEvent::PRE_REFERENCED_RESOVED],
            new PreReferenceResolvedListener()
        );
    }
}
