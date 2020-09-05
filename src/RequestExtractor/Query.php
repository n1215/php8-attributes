<?php

declare(strict_types=1);

namespace N1215\Php8Attributes\RequestExtractor;

use Attribute;
use Psr\Http\Message\ServerRequestInterface;

@@Attribute(Attribute::TARGET_PARAMETER)
class Query
{
    public function __construct(private string $key, private mixed $default = null) {}

    public function extract(ServerRequestInterface $request)
    {
        return $request->getQueryParams()[$this->key] ?? $this->default;
    }
}
