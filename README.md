# php-project-template
Repository template for PHP projects. Sets up composer, docker builds CI with Github Actions, and more.

## PHP
- Ships with version `8.1` (RC5 right now)
- Includes `apcu` and `opcache` extensions for performance
- Includes `pdo-mysql` as a default database connector

## Git
- Configures `.gitignore` for common excludes in a PHP project

## Docker
- Comes with a preconfigured basic Dockerfile
- Comes with a preconfigured `docker-compose` file
- Produces build stages for testing and deployment
- **You will want to customize it**: it uses the built-in web-server, which is _not_ production-ready
- `.dockerignore` also included for build optimizations

## Composer
- Placeholders for library name, description, and PSR-4 autoloading
- Scripts for testing
- Requires current version of PHP
- Includes testing tools (configured) as dev dependencies

## Testing and CI
CI is configured using Github Actions.

- PHPUnit `^9.3` with default configuration (`src`/`tests`).
- PHPStan with strict ruleset, max level, and the PHPUnit extension
- PHP Code Sniffer configured with PSR-12
- Builds and pushes Docker images. The `server` stage is what you'll want to run
