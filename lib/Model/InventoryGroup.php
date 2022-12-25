<?php

declare(strict_types=1);

namespace T3Docs\Intersphinx\Model;

use RuntimeException;

use function array_key_exists;

final class InventoryGroup
{
    /** @var InventoryLink[]  */
    private array $links = [];

    public function addLink(string $key, InventoryLink $link): void
    {
        $this->links[$key] = $link;
    }

    public function hasLink(string $key): bool
    {
        return array_key_exists($key, $this->links);
    }

    public function getLink(string $key): InventoryLink
    {
        if (! array_key_exists($key, $this->links)) {
            throw new RuntimeException('Inventory link with key ' . $key . ' not found. ', 1671398986);
        }

        return $this->links[$key];
    }
}
