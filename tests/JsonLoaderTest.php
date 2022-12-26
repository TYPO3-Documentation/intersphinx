<?php

declare(strict_types=1);

namespace T3Docs\Tests\Intersphinx;

use PHPUnit\Framework\TestCase;
use RuntimeException;
use T3Docs\Intersphinx\Service\JsonLoader;

class JsonLoaderTest extends TestCase
{
    private JsonLoader $jsonLoader;

    protected function setUp(): void
    {
        $this->jsonLoader = new JsonLoader();
    }

    public function testInvalidJsonThrowsError(): void
    {
        $this->expectException(RuntimeException::class);
        $this->jsonLoader->loadJsonFromString('{invalid json');
    }

    public function testNonArrayJsonThrowsError(): void
    {
        $this->expectException(RuntimeException::class);
        $this->jsonLoader->loadJsonFromString('1');
    }

    public function testArrayLoadedFromString(): void
    {
        $json = $this->jsonLoader->loadJsonFromString('{"key": "value"}');
        self::assertArrayHasKey('key', $json);
    }
}
