<?php

declare(strict_types=1);

namespace N1215\Php8Attributes\Enum;

use Attribute;

@@Attribute(Attribute::TARGET_CONSTANT)
class Text
{
    public function __construct(private string $value) {}

    public function getValue(): string
    {
        return $this->value;
    }
}
