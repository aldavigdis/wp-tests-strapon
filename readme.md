# WP-Tests-Strapon

Strap the WordPress PHPUnit tests library onto your WordPress plugin or theme with this handy Composer package.

This PHP Composer package facilitates the configuration and installation of a WordPress test environment so that WordPress' functionality can be used in your PHPUnit test suite.

In essence, it has the same purpose as the `install-wp-tests.sh` shell script installed by the WP-CLI scaffolder, but takes a more streamlined approach:

* It comes as a PHP Composer package, simplifying updates and maintainance, with no extra files in your own codebase
* Is 100% programmed in PHP, so it does not require skills beyond that to contribute to the project
* Has the same dependencies as one can expect from any WordPress development environment (the `cURL` and `ZipArchive` PHP modules)
* Does not depend on ancient technology that nobody should use anymore, such as Subversion
* The test environments are downloaded and configured automatically using your PHPUnit configuration file
* No need to re-run a script to install a development environment with the same parameters after every time you restart your computer or development environment

![A screenshot of WP-Tests-Strapon, showing the test suite being prepared and run](https://github.com/aldavigdis/wp-tests-strapon/blob/main/image.jpg?raw=true)

## Usage

WP-Tests-Strapon integrates with PHPUnit and acts as a bootstrap layer between your WordPress plugin's test suite. Simply run `./vendor/bin/phpunit` as you would generally do after following the installation instructions below.

## Installation

As this in an early development version of this package that has not been uploaded to Packagist yet, you are going to need to add the Git repository manually to your `composer.json` file in order to use Alda's WP-Tests-Strapon.

```json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/aldavigdis/wp-tests-strapon.git"
        }
    ]
}
```

You also need to set the `"minimum-stabiltiy"` attribute in your `composer.json` file to `"dev"`.

Finally, Open up your PHPUnit configuration file (`phpunit.xml` or `phpunit.xml.dist`) and set the `bootstrap` attribute of the root `<phpunit>` element to `vendor/aldavigdis/wp-tests-strapon/bootstrap.php`.

Alternatively, you can include or require ``vendor/aldavigdis/wp-tests-strapon/bootstrap.php`` in your own PHPUnit bootstrap file.

### Example PHPUnit configuration file

```xml
<?xml version="1.0" encoding="UTF-8"?>
<phpunit
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:noNamespaceSchemaLocation="https://schema.phpunit.de/11.0/phpunit.xsd"
    cacheResult="false"
    bootstrap="vendor/aldavigdis/wp-tests-strapon/bootstrap.php"
>
  <php>
    <env name="DB_NAME" value="myawesome_plugin_test" />
  </php>
  <testsuites>
    <testsuite name="Project Test Suite">
      <directory>tests</directory>
    </testsuite>
  </testsuites>
</phpunit>
```

## Environment variables and constants

You can set those in your `phpunit.xml` file, in your terminal session or in your test and production environments.

### PHPUnit Bootstrap

* `WP_TESTS_CONFIG_FILE_PATH` (constant): The location of your test environment's config file. Defaults to `/tmp/wp-tests-strapon/config.php` on Linux/Unix/macOS.
* `WP_VERSION` (environment variable): The PHP version to test against. Defaults to `master`.

### Config File Constants

The following constants will be set in your WordPress test configuration file:

* `WP_DEFAULT_THEME`
* `WP_TESTS_MULTISITE`
* `WP_DEBUG`
* `TABLE_PREFIX`
* `DB_NAME`
* `DB_USER`
* `DB_PASSWORD`
* `DB_HOST`
* `WP_TESTS_DOMAIN`
* `WP_TESTS_EMAIL`
* `WP_TESTS_TITLE`
* `WP_PHP_BINARY`
* `WPLANG`

The default values can be overridden when the file is generated by setting environment variables of the same name. You can do it using your PHPUnit configuration file, the PHPUnit runtime parameters, globally in your terminal or in your CI environment.

Those configuration values are retained in the configuration file between runs. If your config file is broken or something doesn't work, you can run `./vendor/bin/wp-tests-strapon-uninstall` and start over with the correct environment variables set.

## Caveats

After WP-Tests-Strapon hand you over to PHPUnit and the WordPress test suite, you may seem the following text:

```txt
Running as single site... To run multisite, use -c tests/phpunit/multisite.xml
Not running ajax tests. To execute these, use --group ajax.
Not running ms-files tests. To execute these, use --group ms-files.
Not running external-http tests. To execute these, use --group external-http.
```

Those come from WordPress' own test suite and are related to testing WordPress itself. You should ignore those if you are only developing a plugin and are not using test groups of the same name.

## The Todo List

* Test the database connection when the config file is initialised
* Improve documentation
* Add more tests
* Add more glitter
* Test on a Mac
* Get Windows support sorted out (both CMD and PowerShell)

## FAQ

**Q:** What's with the emoji everywhere?

**A:** What's with you being so boring and dull?

## License

Copyright (c) 2024 Alda Vigdís Skarphéðinsdóttir

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

1. The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
2. The Software and its derivatives shall be used for Good, not Evil.
3. Any usage related to non-humanitarian military applications is considered evil under the terms of this license.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

Alternative licensing is available upon request.
