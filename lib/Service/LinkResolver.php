<?php

declare(strict_types=1);

namespace T3Docs\Intersphinx\Service;

use Doctrine\RST\References\ResolvedReference;
use RuntimeException;
use T3Docs\Intersphinx\Intersphinx;
use T3Docs\Intersphinx\Model\Inventory;

use function array_key_exists;
use function explode;
use function substr_count;

final class LinkResolver
{
    /** @var array<String, Inventory> */
    private array $inventories = [];
    private Intersphinx $intersphinx;

    /** @param array<String, Inventory> $inventories */
    public function __construct(Intersphinx $intersphinx, array $inventories)
    {
        $this->intersphinx = $intersphinx;
        $this->inventories = $inventories;
    }

    public function resolveLink(string $link): ?ResolvedReference
    {
        if (substr_count($link, ':') !== 1) {
            $this->intersphinx->getErrorManager()->error('Invalid intersphinx link format: ' . $link);

            return null;
        }

        [$inventoryKey, $linkKey] = explode(':', $link);
        if (! $this->hasInventory($inventoryKey)) {
            $this->intersphinx->getErrorManager()->warning('WARNING: undefined inventor: ' . $inventoryKey);

            return null;
        }

        $inventory = $this->getInventory($inventoryKey);

        $resolvedReference = null;
        foreach ($inventory->getGroups() as $group) {
            if (! $group->hasLink($linkKey)) {
                continue;
            }

            $inventoryLink     = $group->getLink($linkKey);
            $resolvedReference = new ResolvedReference(null, $inventoryLink->getTitle(), $inventory->getBaseUrl() . $inventoryLink->getPath());
        }

        if ($resolvedReference === null) {
            $this->intersphinx->getErrorManager()->warning('WARNING: undefined label: ' . $link);
        }

        return $resolvedReference;
    }

    private function hasInventory(string $key): bool
    {
        return array_key_exists($key, $this->inventories);
    }

    private function getInventory(string $key): Inventory
    {
        if (! array_key_exists($key, $this->inventories)) {
            throw new RuntimeException('Inventory with key ' . $key . ' not found. ', 1671398986);
        }

        return $this->inventories[$key];
    }
}
