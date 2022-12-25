<?php

declare(strict_types=1);

namespace T3Docs\Intersphinx;

use Doctrine\RST\Configuration;
use Doctrine\RST\ErrorManager;
use Doctrine\RST\Event\MissingReferenceResolverEvent;
use T3Docs\Intersphinx\Listener\MissingReferenceResolverListener;

class Intersphinx
{
    private Configuration $configuration;
    private ErrorManager $errorManager;

    public function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;
        $this->errorManager  = new ErrorManager($this->configuration);
        $eventManager        = $this->configuration->getEventManager();
        $eventManager->addEventListener(
            [MissingReferenceResolverEvent::MISSING_REFERENCE_RESOLVER],
            new MissingReferenceResolverListener()
        );
    }

    public function getErrorManager(): ErrorManager
    {
        return $this->errorManager;
    }
}
