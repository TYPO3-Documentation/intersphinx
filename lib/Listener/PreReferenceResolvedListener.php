<?php

declare(strict_types=1);

namespace T3Docs\Intersphinx\Listener;

use Doctrine\RST\Event\PreReferenceResolvedEvent;
use Doctrine\RST\References\ResolvedReference;

use function substr_count;

final class PreReferenceResolvedListener
{
    public function preReferenceResolved(PreReferenceResolvedEvent $event): void
    {
        if (substr_count($event->getData(), ':') !== 1) {
            // Only references of the format documentname:linkname can be resolved
            return;
        }

        $file              = null;
        $title             = 'example';
        $url               = 'https://example.com/';
        $resolvedReference = new ResolvedReference($file, $title, $url);
        $event->setResolvedReference($resolvedReference);
    }
}
