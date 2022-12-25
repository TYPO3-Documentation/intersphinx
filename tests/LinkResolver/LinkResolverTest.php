<?php

declare(strict_types=1);

use Doctrine\RST\ErrorManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use T3Docs\Intersphinx\Intersphinx;
use T3Docs\Intersphinx\Model\Inventory;
use T3Docs\Intersphinx\Model\InventoryGroup;
use T3Docs\Intersphinx\Model\InventoryLink;
use T3Docs\Intersphinx\Service\LinkResolver;

class LinkResolverTest extends TestCase
{
    /** @var Intersphinx|MockObject */
    private $intersphinx;
    /** @var ErrorManager|MockObject */
    private $errorManager;
    private LinkResolver $linkResolver;

    protected function setUp(): void
    {
        $this->intersphinx  = $this->createMock(Intersphinx::class);
        $this->errorManager = $this->createMock(ErrorManager::class);
        $this->intersphinx->method('getErrorManager')->willReturn($this->errorManager);
        $this->linkResolver = new LinkResolver($this->intersphinx, []);
    }

    public function testInvalidLinkSyntaxSetsErrorReturnsNull(): void
    {
        $this->errorManager->expects(self::atLeastOnce())->method('error');
        $resolvedReference = $this->linkResolver->resolveLink('some-invalid-link');
        self::assertNull($resolvedReference);
    }

    public function testInventoryNotFoundSetsWarningReturnsNull(): void
    {
        $this->errorManager->expects(self::atLeastOnce())->method('warning');
        $this->errorManager->expects(self::never())->method('error');
        $resolvedReference = $this->linkResolver->resolveLink('unknown:link');
        self::assertNull($resolvedReference);
    }

    public function testLinkNotFoundSetsWarningReturnsNull(): void
    {
        $linkResolverWithInventory = new LinkResolver($this->intersphinx, ['myinventory' => new Inventory('https://example.com/')]);
        $this->errorManager->expects(self::atLeastOnce())->method('warning');
        $this->errorManager->expects(self::never())->method('error');
        $resolvedReference = $linkResolverWithInventory->resolveLink('myinventory:unkownlink');
        self::assertNull($resolvedReference);
    }

    public function testLinkExistsReturnsResolvedReference(): void
    {
        $linkResolverWithInventory = new LinkResolver($this->intersphinx, ['myinventory' => $this->createFakeInventory()]);
        $this->errorManager->expects(self::never())->method('warning');
        $this->errorManager->expects(self::never())->method('error');
        $resolvedReference = $linkResolverWithInventory->resolveLink('myinventory:link');
        self::assertNotNull($resolvedReference);
    }

    public function testLinkInResolvedReference(): void
    {
        $linkResolverWithInventory = new LinkResolver($this->intersphinx, ['myinventory' => $this->createFakeInventory()]);
        $resolvedReference         = $linkResolverWithInventory->resolveLink('myinventory:link');
        self::assertEquals($resolvedReference->getUrl(), 'https://example.com/Index.html');
    }

    public function testLinkResolvalInMultipleGroups(): void
    {
        $linkResolverWithInventory = new LinkResolver($this->intersphinx, ['myinventory' => $this->createFakeMultipleGroupInventory()]);
        $resolvedReference         = $linkResolverWithInventory->resolveLink('myinventory:link');
        self::assertEquals($resolvedReference->getUrl(), 'https://example.com/Index.html');
    }

    private function createFakeInventory(): Inventory
    {
        $link  = new InventoryLink('<project>', '<version>', 'Index.html', 'Example');
        $group = new InventoryGroup();
        $group->addLink('link', $link);
        $inventory = new Inventory('https://example.com/');
        $inventory->addGroup('std:label', $group);

        return $inventory;
    }

    private function createFakeMultipleGroupInventory(): Inventory
    {
        $link   = new InventoryLink('<project>', '<version>', 'Index.html', 'Example');
        $group1 = new InventoryGroup();
        $group2 = new InventoryGroup();
        $group2->addLink('link', $link);
        $group3    = new InventoryGroup();
        $inventory = new Inventory('https://example.com/');
        $inventory->addGroup('std:group1', $group1);
        $inventory->addGroup('std:group2', $group2);
        $inventory->addGroup('std:group3', $group3);

        return $inventory;
    }
}
