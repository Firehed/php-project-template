<?php

declare(strict_types=1);

use function Firehed\Container\env;

return [
    'environment' => env('ENVIRONMENT'),

    'isDevMode' => fn ($c) => $c->get('environment') === 'development',
];
