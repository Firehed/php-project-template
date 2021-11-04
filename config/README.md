# Application config

By default, all PHP files in this directory that are not explicitly excluded (see `$blocklist` in `/config.php`) will be used for application configuration.
Each file must return an array containing the configured values.
See the [config library documentation](https://github.com/Firehed/container) for more details.
There are no naming requirements, but it's highly recommended to use fully-qualified class or interface names as keys for any value that returns an object.
