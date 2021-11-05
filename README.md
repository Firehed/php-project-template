# php-project-template
Repository template for PHP projects. Sets up composer, docker builds CI with Github Actions, and more.

## How to use this

The easiest way to use this is clicking the green "use this template" button on Github, or use [this link](https://github.com/Firehed/php-project-template/generate).
You may also want to manually clone it (e.g. for use outside of Github).

If desired, you can cherry-pick components and/or configuration settings to manually use with your existing project.
It's totally up to you!

This template assumes you're already generally familar with the PHP ecosystem, along with [commonly accepted best practices](https://phptherightway.com).

### Configuration & Setup

This template is somewhat opinionated in order to provide a useful starting point for projects.
If your opinions differ, that's fine!
Feel free to adjust packages, settings, and anything else you want after creating a project from this template.
Whether you like this as-is or want to swap out basically everything, there are still some **required** modifications for this to be useful:

#### Required changes

- The included Dockerfile for the application's server uses the built-in PHP web-server.
  This is **not suitable** at all for production deployments.
  There are comments in the Dockerfile detailing this, including recommended alternatives.
  Since this decision tends to be infrastructure-specific, it was intentionally left un-opinionated.

- Composer has some placeholder values in place that you'll want to fill in:
  - `name`
  - `description`
  - `license` (see footnote)
  - `authors`
  - `autoload` and `autoload-dev` namespaces

- `docker-compose` has some pre-filled values for credentials and connection info.
  You'll want to modify them (`MYSQL_*`) to something better suited to your project.

- This supports `.env`, but only includes an example file.
  Copy `.env.example` to `.env` in the project root (and set values as needed).
  The `DATABASE_URL` value must match the `docker-compose` file's contents.

- `public/index.php` is only a basic "Hello, World!" that includes the bootstrap file.
  That's pretty useless, but what you need is entirely framework-dependent.

#### Highly-recommended changes

- No logger is provided or configured.
  Whatever you select should write to `STDOUT` (or `/dev/stdout`), as that's where Docker wants messages to go.
- No global/fallback error handling is configred.
  You should set one up with `set_exception_handler` in `bootstrap.php`.

#### Optional changes

- You may want to adjust the `PORT` in the Dockerfile and docker-compose
- You may want a different version of PHP (though for a new project, there's little reason to not use the latest one)
- Doctrine (if you keep it) uses the new PHP 8 `Attribute` driver.
  This varies from the documentation default, which uses the annotation driver (`/** @Entity */`, etc)
  It and other Doctrine settings are in `config/doctrine.php`.
- Default

---
# What's Included

## PHP
- Ships with version `8.1` (RC5 right now)
- Includes `apcu` and `opcache` extensions for performance
- Includes `pdo-mysql` as a default database connector

### Code Quality
- Includes PHPUnit, PHPCS, and PHPStan
- All are preconfigured and run during CI (See below)

### Configuration
- Provides a PSR-11 container for DI
- Sets up support for `.env`

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

## Database
- Comes with MySQL 8 in docker-compose, and the application configured to connect to it
- Comes with `doctrine/orm` pre-configured (using PHP 8 Attributes)
- Comes with `doctrine/migrations` pre-configured for schema changes

## Testing and CI
CI is configured using Github Actions.

- PHPUnit `^9.3` with default configuration (`src`/`tests`).
    - Produces code coverage report
    - Uploads to `codecov`
- PHPStan with strict ruleset, max level, and the PHPUnit extension
- PHP Code Sniffer configured with PSR-12
- Builds and pushes Docker images. The `server` stage is what you'll want to run

---
## License
This template is MIT licensed.
There is a `LICENSE` file in the repository root matching that.

Projects using this template (in part or in whole) are free to use any license they desire, so long as it's compatible with the licenses of the dependencies.
Such uses are not required to retain the `LICENSE` file or copyright notices - although credit is appreciated.

Forks of this template (intended for use as a template on their own) must adhere to the license of the template itself, including retention of the copyright notice(s).
