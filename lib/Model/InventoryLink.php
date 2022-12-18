<?php

declare(strict_types=1);

namespace T3Docs\Intersphinx\Model;

use RuntimeException;

use function htmlspecialchars;
use function preg_match;

final class InventoryLink
{
    private string $project;
    private string $version;
    private string $path;
    private string $title;

    public function __construct(
        string $project,
        string $version,
        string $path,
        string $title
    ) {
        $this->project = htmlspecialchars($project);
        $this->version = htmlspecialchars($version);
        if (preg_match('/^([a-zA-Z0-9-_]+\/)*([a-zA-Z0-9-_])+\.html(#[a-zA-Z0-9-_]*)?$/', $path) === false) {
            throw new RuntimeException('Inventory link "' . $path . '" has an invalid scheme. ', 1671398986);
        }

        $this->path  = $path;
        $this->title = htmlspecialchars($title);
    }

    public function getProject(): string
    {
        return $this->project;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getTitle(): string
    {
        return $this->title;
    }
}
