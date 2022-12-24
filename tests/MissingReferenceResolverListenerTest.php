<?php

declare(strict_types=1);

namespace T3Docs\Tests\Intersphinx;

use Doctrine\RST\Environment;
use Doctrine\RST\Event\MissingReferenceResolverEvent;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use T3Docs\Intersphinx\Listener\MissingReferenceResolverListener;

final class MissingReferenceResolverListenerTest extends TestCase
{
    private MissingReferenceResolverListener $listener;

    /** @var Environment|MockObject */
    private $environment;

    protected function setUp(): void
    {
        $this->listener    = new MissingReferenceResolverListener();
        $this->environment = $this->createMock(Environment::class);
    }

    public function testDataWithoutColonLeavesResolvedEventNull(): void
    {
        $event = new MissingReferenceResolverEvent($this->environment, 'lorem-ipsum', []);
        $this->listener->resolveMissingReference($event);
        self::assertNull($event->getResolvedReference());
    }

    public function testDataWitMultipleColonLeavesResolvedEventNull(): void
    {
        $event = new MissingReferenceResolverEvent($this->environment, 'lorem:ipsum:dolor', []);
        $this->listener->resolveMissingReference($event);
        self::assertNull($event->getResolvedReference());
    }

    public function testDataWitOneColonSetsResolvedReference(): void
    {
        $event = new MissingReferenceResolverEvent($this->environment, 'lorem:ipsum', []);
        $this->listener->resolveMissingReference($event);
        self::assertNotNull($event->getResolvedReference());
    }
}
