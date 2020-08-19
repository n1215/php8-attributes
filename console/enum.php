<?php
declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use N1215\Php8Attributes\Enum\Fruit;

$lines = array_map(
    callback: fn(Fruit $fruit) => match($fruit->getValue()){
        'apple' => "{$fruit?->getValue()}: {$fruit->getText()}!!!",
        default => "{$fruit?->getValue()}: {$fruit->getText()}",
    },
    arr1: Fruit::all(),
);

echo join(
    pieces: $lines,
    glue: PHP_EOL,
) . PHP_EOL;

assert(Fruit::APPLE() === Fruit::APPLE());
assert(Fruit::ORANGE() === Fruit::ORANGE());
assert(Fruit::PEACH() === Fruit::PEACH());
