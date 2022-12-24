<?php

declare(strict_types=1);

namespace T3Docs\Intersphinx;

use Doctrine\RST\Configuration;
use Doctrine\RST\Event\MissingReferenceResolverEvent;
use T3Docs\Intersphinx\Listener\MissingReferenceResolverListener;

final class Intersphinx
{
    private Configuration $configuration;

    public function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;
        $eventManager        = $this->configuration->getEventManager();
        $eventManager->addEventListener(
            [MissingReferenceResolverEvent::MISSING_REFERENCE_RESOLVER],
            new MissingReferenceResolverListener()
        );
    }
}
