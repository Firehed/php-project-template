<?php

declare(strict_types=1);

// Run configuration inside of a closure to prevent the intermediate variables
// leaking into the scope of the file including this.
return (function () {
    $compile = getenv('ENVIRONMENT') !== 'development';
    if ($compile) {
        $builder = new Firehed\Container\Compiler('.generated/config.php');
    } else {
        $builder = new Firehed\Container\Builder();
    }

    $blocklist = [
        'config/cli-config.php', // Doctrine ORM
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
})();
