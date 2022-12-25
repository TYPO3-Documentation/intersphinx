<?php

declare(strict_types=1);

namespace T3Docs\Intersphinx\Service;

use Doctrine\RST\ErrorManager;
use Doctrine\RST\References\ResolvedReference;
use T3Docs\Intersphinx\Repository\InventoryRepository;

use function explode;
use function substr_count;

final class LinkResolver
{
    private InventoryRepository $inventoryRepository;
    private ErrorManager $errorManager;

    public function __construct(
        InventoryRepository $inventoryRepository,
        ErrorManager $errorManager
    ) {
        $this->inventoryRepository = $inventoryRepository;
        $this->errorManager        = $errorManager;
    }

    public function resolveLink(string $link): ?ResolvedReference
    {
        if (substr_count($link, ':') !== 1) {
            $this->errorManager->error('Invalid intersphinx link format: ' . $link);

            return null;
        }

        [$inventoryKey, $linkKey] = explode(':', $link);
        if (! $this->inventoryRepository->hasInventory($inventoryKey)) {
            $this->errorManager->warning('WARNING: undefined inventor: ' . $inventoryKey);

            return null;
        }

        $inventory = $this->inventoryRepository->getInventory($inventoryKey);

        $resolvedReference = null;
        foreach ($inventory->getGroups() as $group) {
            if (! $group->hasLink($linkKey)) {
                continue;
            }

            $inventoryLink     = $group->getLink($linkKey);
            $resolvedReference = new ResolvedReference(null, $inventoryLink->getTitle(), $inventory->getBaseUrl() . $inventoryLink->getPath());
        }

        if ($resolvedReference === null) {
            $this->errorManager->warning('WARNING: undefined label: ' . $link);
        }

        return $resolvedReference;
    }
}
