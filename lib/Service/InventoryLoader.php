<?php

declare(strict_types=1);

namespace T3Docs\Intersphinx\Service;

use T3Docs\Intersphinx\Model\Inventory;
use T3Docs\Intersphinx\Model\InventoryGroup;
use T3Docs\Intersphinx\Model\InventoryLink;
use T3Docs\Intersphinx\Repository\InventoryRepository;

final class InventoryLoader
{
    private InventoryRepository $inventoryRepository;
    private JsonLoader $jsonLoader;

    public function __construct(?InventoryRepository $inventoryRepository = null, ?JsonLoader $jsonLoader = null)
    {
        $this->inventoryRepository = $inventoryRepository ?? (new InventoryRepository([]));
        $this->jsonLoader          = $jsonLoader ?? (new JsonLoader());
    }

    public function getInventoryRepository(): InventoryRepository
    {
        return $this->inventoryRepository;
    }

    /** @param array<String, mixed> $json */
    public function loadInventoryFromJson(string $key, string $baseUrl, array $json): void
    {
        $newInventory = new Inventory($baseUrl);
        foreach ($json as $groupKey => $groupArray) {
            $group = new InventoryGroup();
            foreach ($groupArray as $linkKey => $linkArray) {
                $link = new InventoryLink($linkArray[0], $linkArray[1], $linkArray[2], $linkArray[3]);
                $group->addLink($linkKey, $link);
            }

            $newInventory->addGroup($groupKey, $group);
        }

        $this->inventoryRepository->addInventory($key, $newInventory);
    }

    public function loadInventoryFromUrl(string $key, string $url): void
    {
        $json = $this->jsonLoader->loadJsonFromUrl($url);

        $this->loadInventoryFromJson($key, $url, $json);
    }
}
