<?php

declare(strict_types=1);

chdir(__DIR__);
date_default_timezone_set('UTC');
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
ini_set('error_log', '/dev/stdout'); // Docker wants logs written to stdout
ini_set('error_reporting', (string)E_ALL);
ini_set('log_errors', '1');

// Convert errors to exceptions. This will receive all errors (as configured in
// the second argument), but ignore anything outside of the `error_reporting`
// value set above.
set_error_handler(function (int $severity, string $message, string $file, int $line): bool {
    // If the current error_reporting error level matches the specified
    // severity, convert it to an error exception. With this check, `@` error
    // suppression (or changes to `error_reporting` above) are respected.
    if ((error_reporting() & $severity) !== 0) {
        throw new ErrorException($message, 0, $severity, $file, $line);
    }
    // Always log deprecation warnings even if they're turned off at runtime.
    // If running inside unit tests, throw anyway.
    if ($severity === E_USER_DEPRECATED) {
        if (defined('PHPUNIT_COMPOSER_INSTALL')) {
            throw new ErrorException($message, 0, $severity, $file, $line);
        } else {
            error_log($message . ' File: ' . $file . ' Line: ' . $line);
        }
    }
    return false;
}, E_ALL);

require 'vendor/autoload.php';

if (file_exists(__DIR__ . '/.env')) {
    // unsafe: uses .env to putenv() the values to getenv() and the superglobals
    // mutable: .env wins over native environment
    $dotenv = Dotenv\Dotenv::createUnsafeMutable(__DIR__);
    $dotenv->load();
}
