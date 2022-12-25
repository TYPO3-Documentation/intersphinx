<?php

declare(strict_types=1);

namespace T3Docs\Tests\Intersphinx;

use Doctrine\Common\EventManager;
use Doctrine\RST\Configuration;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use T3Docs\Intersphinx\Intersphinx;

class IntersphinxTest extends TestCase
{
    /** @var Configuration|MockObject */
    private $configuration;

    /** @var EventManager|MockObject */
    private $eventManager;

    protected function setUp(): void
    {
        $this->configuration = $this->createMock(Configuration::class);
        $this->eventManager  = $this->createMock(EventManager::class);
        $this->configuration->method('getEventManager')
            ->willReturn($this->eventManager);
    }

    public function testIntersphinxConstructorRegistersEvent(): void
    {
        $this->eventManager->expects(self::atLeastOnce())
            ->method('addEventListener');
        new Intersphinx($this->configuration);
    }
}
