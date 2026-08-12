<?php

declare(strict_types=1);

// This file is automatically detected and used by Doctrine

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Firehed\Container\TypedContainerInterface;

require_once 'bootstrap.php';
$config = require 'config.php';
assert($config instanceof TypedContainerInterface);

return ConsoleRunner::createHelperSet($config->get(EntityManagerInterface::class));
