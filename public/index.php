<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Psr\Http\Message\ServerRequestInterface;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\ServerRequestCreator;
use N1215\Php8Attributes\RequestExtractor\HeaderLine;
use N1215\Php8Attributes\RequestExtractor\IpAddress;
use N1215\Php8Attributes\RequestExtractor\Query;

$psr17Factory = new Psr17Factory();
$creator = new ServerRequestCreator(
    $psr17Factory, // ServerRequestFactory
    $psr17Factory, // UriFactory
    $psr17Factory, // UploadedFileFactory
    $psr17Factory  // StreamFactory
);

$request = $creator->fromGlobals();

function handle(ServerRequestInterface $request, callable $action)
{
    $reflectionFunction = new ReflectionFunction($action);
    $reflectionParameters = $reflectionFunction->getParameters();

    $parameters = array_map(function (ReflectionParameter $refParam) use ($request) {
        $attribute = $refParam->getAttributes()[0];
        $className = $attribute->getName();
        $arguments = $attribute->getArguments();

        // エラーになる
        // $attributeInstance = $attribute->newInstance();

        return (new $className(...$arguments))->extract($request);
    }, $reflectionParameters);

    return $action(...$parameters);
}

@@Get('/hello')
function hello(
    @@Query('name', '誰か') ?string $name,
    @@IpAddress ?string $ipAddress
): string {
    return "こんにちは、{$name} さん。あなたのIPアドレスは{$ipAddress}です！";
}



echo handle($request, 'hello');
