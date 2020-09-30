<?php

declare(strict_types=1);

namespace N1215\Php8Attributes\Routing;

/**
 * 仮
 */
#[Attribute(Attribute::TARGET_FUNCTION | Attribute::TARGET_METHOD)]
class Get
{
    public function __construct(private string $path) {}

    public function getPath(): string
    {
        return $this->path;
    }
}
