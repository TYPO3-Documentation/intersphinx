<?php

declare(strict_types=1);

namespace T3Docs\Intersphinx\Service;

use JsonException;
use RuntimeException;

use function file_get_contents;
use function is_array;
use function json_decode;

use const JSON_THROW_ON_ERROR;

class JsonWriter
{
    /**
     * @param array<mixed> $json
     */
    public function saveJsonToFile(array $json, string $filename): void
    {
        $jsonString = json_encode($json, JSON_PRETTY_PRINT, JSON_THROW_ON_ERROR);
        file_put_contents($filename, $jsonString);
    }
}
