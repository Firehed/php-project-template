<?php

declare(strict_types=1);

use Firehed\Container\TypedContainerInterface;

use function Firehed\Container\env;

return [
    'environment' => env('ENVIRONMENT'),

    'isDevMode' => fn (TypedContainerInterface $c): bool => $c->getString('environment') === 'development',
];
