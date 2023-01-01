<?php

declare(strict_types=1);

namespace T3Docs\Intersphinx\Service;

use function file_put_contents;
use function json_encode;

use const JSON_PRETTY_PRINT;
use const JSON_THROW_ON_ERROR;

class JsonWriter
{
    /** @param array<mixed> $json */
    public function saveJsonToFile(array $json, string $filename): void
    {
        $jsonString = json_encode($json, JSON_PRETTY_PRINT, JSON_THROW_ON_ERROR);
        file_put_contents($filename, $jsonString);
    }
}
