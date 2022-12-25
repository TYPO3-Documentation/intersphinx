<?php

declare(strict_types=1);

namespace T3Docs\Tests\Intersphinx;

use Doctrine\RST\Environment;
use Doctrine\RST\Event\MissingReferenceResolverEvent;
use Doctrine\RST\References\ResolvedReference;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use T3Docs\Intersphinx\Listener\MissingReferenceResolverListener;
use T3Docs\Intersphinx\Service\LinkResolver;

final class MissingReferenceResolverListenerTest extends TestCase
{
    private MissingReferenceResolverListener $listener;

    /** @var Environment|MockObject */
    private $environment;

    /** @var LinkResolver|MockObject */
    private $linkResolver;

    protected function setUp(): void
    {
        $this->environment  = $this->createMock(Environment::class);
        $this->linkResolver = $this->createMock(LinkResolver::class);
        $this->listener     = new MissingReferenceResolverListener($this->linkResolver);
    }

    public function testDataWithoutColonLeavesResolvedEventNull(): void
    {
        $event = new MissingReferenceResolverEvent(
            $this->environment,
            'lorem-ipsum',
            []
        );
        $this->listener->resolveMissingReference($event);
        self::assertNull($event->getResolvedReference());
    }

    public function testDataWitMultipleColonLeavesResolvedEventNull(): void
    {
        $event = new MissingReferenceResolverEvent(
            $this->environment,
            'lorem:ipsum:dolor',
            []
        );
        $this->listener->resolveMissingReference($event);
        self::assertNull($event->getResolvedReference());
    }

    public function testDataWitOneColonSetsResolvedReference(): void
    {
        $event             = new MissingReferenceResolverEvent(
            $this->environment,
            'lorem:ipsum',
            []
        );
        $resolvedReference = new ResolvedReference(null, null, null, []);
        $this->linkResolver->expects(self::atLeastOnce())->method('resolveLink')->willReturn($resolvedReference);
        $this->listener->resolveMissingReference($event);
        self::assertEquals($resolvedReference, $event->getResolvedReference());
    }
}
