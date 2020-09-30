<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Psr\Http\Message\ServerRequestInterface;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\ServerRequestCreator;
use N1215\Php8Attributes\RequestExtractor\HeaderLine;
use N1215\Php8Attributes\RequestExtractor\IpAddress;
use N1215\Php8Attributes\RequestExtractor\Query;
use N1215\Php8Attributes\Responder\TextResponder;
use N1215\Php8Attributes\Routing\Get;

$psr17Factory = new Psr17Factory();
$creator = new ServerRequestCreator(
    serverRequestFactory: $psr17Factory,
    uriFactory: $psr17Factory,
    uploadedFileFactory: $psr17Factory,
    streamFactory: $psr17Factory,
);

$request = $creator->fromGlobals();

function handle(ServerRequestInterface $request, callable $action)
{
    $reflectionFunction = new ReflectionFunction($action);
    $reflectionParameters = $reflectionFunction->getParameters();

    $parameters = array_map(function (ReflectionParameter $refParam) use ($request) {
        $attribute = $refParam->getAttributes()[0];
        return $attribute->newInstance()->extract($request);
    }, $reflectionParameters);

    $output = $action(...$parameters);

    $reflectionAttributes = $reflectionFunction->getAttributes();
    $responderAttribute = array_values(array_filter($reflectionAttributes, function (ReflectionAttribute $reflectionAttribute) {
        return $reflectionAttribute->getName() === TextResponder::class;
    }))[0];

    return $responderAttribute->newInstance()->respond($output);
}

#[Get('/hello')]
#[TextResponder]
function hello(
    #[Query('name', '誰か')] ?string $name,
    #[IpAddress] ?string $ipAddress
): string {
    return "こんにちは、{$name} さん。あなたのIPアドレスは {$ipAddress} です！";
}

$response = handle($request, 'hello');

(new SapiEmitter())->emit($response);
