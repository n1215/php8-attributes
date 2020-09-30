<?php

declare(strict_types=1);

namespace N1215\Php8Attributes\RequestExtractor;

use Attribute;
use Psr\Http\Message\ServerRequestInterface;

#[Attribute(Attribute::TARGET_PARAMETER)]
class HeaderLine
{
    public function __construct(private string $key) {}

    public function extract(ServerRequestInterface $request)
    {
        return $request->getHeader($this->key)[0] ?? null;
    }
}
