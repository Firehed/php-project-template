<?php

declare(strict_types=1);

chdir(__DIR__);
date_default_timezone_set('UTC');
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
ini_set('error_log', '/dev/stdout'); // Docker wants logs written to stdout
ini_set('error_reporting', (string)E_ALL);
ini_set('log_errors', '1');

require 'vendor/autoload.php';

if (file_exists(__DIR__ . '/.env')) {
    // unsafe: uses .env to putenv() the values to getenv() and the superglobals
    // mutable: .env wins over native environment
    $dotenv = Dotenv\Dotenv::createUnsafeMutable(__DIR__);
    $dotenv->load();
}
