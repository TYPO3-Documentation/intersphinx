<?php

declare(strict_types=1);

namespace T3Docs\Intersphinx\Service;

use Doctrine\RST\Environment;
use Doctrine\RST\Meta\MetaEntry;
use Doctrine\RST\Meta\Metas;

use function is_array;

final class InventoryWriter
{
    private JsonWriter $jsonWriter;

    public function __construct(?JsonWriter $jsonWriter = null)
    {
        $this->jsonWriter = $jsonWriter ?? (new JsonWriter());
    }

    public function saveMetasToJson(Metas $metas, string $filename): void
    {
        $inventories = [
            'std:doc' => [],
            'std:label' => [],
        ];
        foreach ($metas->getAll() as $metaEntry) {
            $inventories['std:doc'][$metaEntry->getFile()] = [
                '<project>',
                '<version>',
                $metaEntry->getUrl(),
                $metaEntry->getTitle(),
            ];
            foreach ($metaEntry->getTitles() as $title) {
                $this->addInventories($metaEntry, $title, $inventories);
            }
        }

        $this->jsonWriter->saveJsonToFile($inventories, $filename);
    }

    /**
     * @param mixed[]              $title
     * @param array<String, mixed> $inventories
     */
    private function addInventories(
        MetaEntry $metaEntry,
        array $title,
        array &$inventories
    ): void {
        $anchor                            = Environment::slugify($title[0]);
        $inventories['std:label'][$anchor] = [
            '<project>',
            '<version>',
            $metaEntry->getUrl() . '#' . $anchor,
            $title[0],
        ];
        if (! is_array($title[1])) {
            return;
        }

        foreach ($title[1] as $subtitle) {
            $this->addInventories($metaEntry, $subtitle, $inventories);
        }
    }
}
