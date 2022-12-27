<?php

declare(strict_types=1);

namespace T3Docs\Tests\Intersphinx;

use Doctrine\Common\EventManager;
use Doctrine\RST\Configuration;
use Doctrine\RST\Event\MissingReferenceResolverEvent;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use T3Docs\Intersphinx\Intersphinx;
use T3Docs\Intersphinx\Repository\InventoryRepository;

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

    public function testIntersphinxConstructorRegisters2Events(): void
    {
        $this->eventManager->expects(self::exactly(2))
            ->method('addEventListener')->withConsecutive(
                [[MissingReferenceResolverEvent::MISSING_REFERENCE_RESOLVER]]
            );
        new Intersphinx($this->configuration, new InventoryRepository([]));
    }
}
