<?php

declare(strict_types=1);

namespace T3Docs\Intersphinx\Service;

use RuntimeException;
use T3Docs\Intersphinx\Model\Inventory;
use T3Docs\Intersphinx\Model\InventoryGroup;
use T3Docs\Intersphinx\Model\InventoryLink;
use T3Docs\Intersphinx\Repository\InventoryRepository;

use function file_get_contents;
use function json_decode;

use const JSON_THROW_ON_ERROR;

final class InventoryLoader
{
    private InventoryRepository $inventoryRepository;

    public function __construct(?InventoryRepository $inventoryRepository = null)
    {
        $this->inventoryRepository = $inventoryRepository ?? (new InventoryRepository([]));
    }

    public function getInventoryRepository(): InventoryRepository
    {
        return $this->inventoryRepository;
    }

    public function loadInventoryFromString(string $key, string $jsonString): void
    {
        $json         = json_decode($jsonString, true, 512, JSON_THROW_ON_ERROR);
        $newInventory = new Inventory('https://example.com/');
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
        $jsonString = file_get_contents($url);
        if ($jsonString === false) {
            throw new RuntimeException('URL ' . $url . ' not found. ', 1671398986);
        }

        $this->loadInventoryFromString($key, $jsonString);
    }
}
