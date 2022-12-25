<?php

declare(strict_types=1);

namespace T3Docs\Intersphinx\Repository;

use RuntimeException;
use T3Docs\Intersphinx\Model\Inventory;

use function array_key_exists;

final class InventoryRepository
{
    /** @var array<String, Inventory> */
    private array $inventories;

    /** @param array<String, Inventory> $inventories */
    public function __construct(array $inventories)
    {
        $this->inventories = $inventories;
    }

    public function hasInventory(string $key): bool
    {
        return array_key_exists($key, $this->inventories);
    }

    public function getInventory(string $key): Inventory
    {
        if (! $this->hasInventory($key)) {
            throw new RuntimeException('Inventory with key ' . $key . ' not found. ', 1671398986);
        }

        return $this->inventories[$key];
    }

    public function addInventory(string $key, Inventory $inventory): void
    {
        $this->inventories[$key] = $inventory;
    }
}
