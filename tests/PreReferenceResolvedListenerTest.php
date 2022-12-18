<?php

declare(strict_types=1);

namespace T3Docs\Tests\Intersphinx;

use Doctrine\RST\Environment;
use Doctrine\RST\Event\PreReferenceResolvedEvent;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use T3Docs\Intersphinx\Listener\PreReferenceResolvedListener;

final class PreReferenceResolvedListenerTest extends TestCase
{
    private PreReferenceResolvedListener $listener;

    /** @var Environment|MockObject */
    private $environment;

    protected function setUp(): void
    {
        $this->listener    = new PreReferenceResolvedListener();
        $this->environment = $this->createMock(Environment::class);
    }

    public function testDataWithoutColonLeavesResolvedEventNull(): void
    {
        $event = new PreReferenceResolvedEvent($this->environment, 'lorem-ipsum', []);
        $this->listener->preReferenceResolved($event);
        self::assertNull($event->getResolvedReference());
    }

    public function testDataWitMultipleColonLeavesResolvedEventNull(): void
    {
        $event = new PreReferenceResolvedEvent($this->environment, 'lorem:ipsum:dolor', []);
        $this->listener->preReferenceResolved($event);
        self::assertNull($event->getResolvedReference());
    }

    public function testDataWitOneColonSetsResolvedReference(): void
    {
        $event = new PreReferenceResolvedEvent($this->environment, 'lorem:ipsum', []);
        $this->listener->preReferenceResolved($event);
        self::assertNotNull($event->getResolvedReference());
    }
}
