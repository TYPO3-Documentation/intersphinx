<?php

declare(strict_types=1);

namespace T3Docs\Intersphinx\Listener;

use Doctrine\RST\Event\MissingReferenceResolverEvent;
use T3Docs\Intersphinx\Service\LinkResolver;

use function substr_count;

final class MissingReferenceResolverListener
{
    private LinkResolver $linkResolver;

    public function __construct(LinkResolver $linkResolver)
    {
        $this->linkResolver = $linkResolver;
    }

    public function resolveMissingReference(MissingReferenceResolverEvent $event): void
    {
        if (substr_count($event->getData(), ':') !== 1) {
            // Only references of the format documentname:linkname can be resolved
            return;
        }

        $resolvedReference = $this->linkResolver->resolveLink($event->getData());
        $event->setResolvedReference($resolvedReference);
    }
}
