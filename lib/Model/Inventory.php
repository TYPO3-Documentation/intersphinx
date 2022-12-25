<?php

declare(strict_types=1);

namespace T3Docs\Intersphinx\Model;

use RuntimeException;

use function array_key_exists;

final class Inventory
{
    /** @var InventoryGroup[]  */
    private array $groups = [];
    private string $baseUrl;

    /** @param String $baseUrl */
    public function __construct(string $baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    public function addGroup(string $key, InventoryGroup $group): void
    {
        $this->groups[$key] = $group;
    }

    /** @return InventoryGroup[] */
    public function getGroups(): array
    {
        return $this->groups;
    }

    public function getInventory(string $key): InventoryGroup
    {
        if (! array_key_exists($key, $this->groups)) {
            throw new RuntimeException('Inventory group with key ' . $key . ' not found. ', 1671398986);
        }

        return $this->groups[$key];
    }

    public function getLink(string $group, string $key): InventoryLink
    {
        return $this->getInventory($group)->getLink($key);
    }
}
