<?php

declare(strict_types=1);

namespace N1215\Php8Attributes\Responder;

use Attribute;
use Nyholm\Psr7\Response;

@@Attribute
class TextResponder
{
    /**
     * @param string $text
     * @return Response
     */
    public function respond(string $text): Response
    {
        return new Response(200, ['Content-Type' => 'text/plain'], $text);
    }
}
