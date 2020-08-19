<?php

declare(strict_types=1);

namespace N1215\Php8Attributes\RequestExtractor;

use Psr\Http\Message\ServerRequestInterface;

@@Attribute(Attribute::TARGET_PARAMETER)
class IpAddress
{
    public function extract(ServerRequestInterface $request)
    {
        return $request->getServerParams()['REMOTE_ADDR'];
    }
}
