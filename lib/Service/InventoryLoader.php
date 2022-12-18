<?php

declare(strict_types=1);

namespace T3Docs\Intersphinx\Service;

use RuntimeException;
use T3Docs\Intersphinx\Model\Inventory;
use T3Docs\Intersphinx\Model\InventoryGroup;
use T3Docs\Intersphinx\Model\InventoryLink;

use function array_key_exists;
use function file_get_contents;
use function json_decode;
use function print_r;

final class InventoryLoader
{
    private static ?InventoryLoader $myself = null;
    /** @var Inventory[] */
    private array $inventories = [];

    public static function getInventoryLoader(): InventoryLoader
    {
        if (self::$myself === null) {
            self::$myself = new InventoryLoader();
        }

        return self::$myself;
    }

    public function loadInventoryFromString(string $key, string $jsonString): void
    {
        $json = json_decode($jsonString, true);
        print_r($json);
        $this->inventories[$key] = new Inventory();
        foreach ($json as $groupKey => $groupArray) {
            $group = new InventoryGroup();
            foreach ($groupArray as $linkKey => $linkArray) {
                $link = new InventoryLink($linkArray[0], $linkArray[1], $linkArray[2], $linkArray[3]);
                $group->addLink($linkKey, $link);
            }

            $this->inventories[$key]->addGroup($groupKey, $group);
        }
    }

    public function loadInventoryFromUrl(string $key, string $url): void
    {
        $jsonString = file_get_contents($url);
        if ($jsonString === false) {
            throw new RuntimeException('URL ' . $url . ' not found. ', 1671398986);
        }

        $this->loadInventoryFromString($key, $jsonString);
    }

    public function getInventory(string $key): Inventory
    {
        if (! array_key_exists($key, $this->inventories)) {
            throw new RuntimeException('Inventory with key ' . $key . ' not found. ', 1671398986);
        }

        return $this->inventories[$key];
    }
}
