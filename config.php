<?php

declare(strict_types=1);

$compile = getenv('ENVIRONMENT') !== 'development';
if ($compile) {
    $builder = new Firehed\Container\Compiler('.generated/config.php');
} else {
    $builder = new Firehed\Container\Builder();
}

$blocklist = [
    'config/cli-config.php', // Doctrine ORM
    'config/migrations.php', // Doctrine Migrations
];

$files = glob('config/*.php');
assert($files !== false);

foreach ($files as $file) {
    // Skip blocked file
    if (in_array($file, $blocklist, true)) {
        continue;
    }
    $builder->addFile($file);
}

return $builder->build();
