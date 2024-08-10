# Clean your strings from XSS threats

[![Latest Version on Packagist](https://img.shields.io/packagist/v/medilies/xssless.svg?style=flat-square)](https://packagist.org/packages/medilies/xssless)
[![pest](https://img.shields.io/github/actions/workflow/status/medilies/xssless/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/medilies/xssless/actions?query=workflow%3Arun-tests+branch%3Amain)
[![phpstan](https://img.shields.io/github/actions/workflow/status/medilies/xssless/phpstan.yml?branch=main&label=phpstan&style=flat-square)](https://github.com/medilies/xssless/actions?query=workflow%3A"phpstan"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/medilies/xssless.svg?style=flat-square)](https://packagist.org/packages/medilies/xssless)

![workflow](./workflow.png)

## Why use Xssless

- **Prevent XSS Attacks:** Safeguard your application by cleaning user-submitted HTML, eliminating potential XSS threats.
- **Painless HTML 5 support:** Xssless leverages engines with the best HTML5 support.
- **Easy to build policies:** (todo).

The default driver aligns with [OWASP](https://cheatsheetseries.owasp.org/cheatsheets/Cross_Site_Scripting_Prevention_Cheat_Sheet.html#html-sanitization) recommendations:

> HTML Sanitization will strip dangerous HTML from a variable and return a safe string of HTML. OWASP recommends **DOMPurify** for HTML Sanitization.

## Requirements

- PHP >= 8.2
- ext-json
- Node >= 18
- NPM

## Installation

Install the package via composer:

```bash
composer require medilies/xssless
```

For non Laravel projects pick a config and run the following code:

```php
$config = new Medilies\Xssless\Dompurify\DompurifyCliConfig('node', 'npm');

(new Medilies\Xssless\Xssless)
    ->using($config)
    ->setup();
```

### Laravel setup

You can publish the config file with:

```bash
php artisan vendor:publish --tag="xssless-config"
```

This is the contents of the published config file:

```php
return [
    'default' => 'dompurify-cli',

    'drivers' => [
        'dompurify-cli' => new DompurifyCliConfig(
            node: env('NODE_PATH'),
            npm: env('NPM_PATH'),
            binary: null,
            tempFolder: null,
        ),
        'dompurify-service' => new DompurifyServiceConfig(
            node: env('NODE_PATH'),
            npm: env('NPM_PATH'),
            host: '127.0.0.1',
            port: 63000,
            binary: null,
        ),
    ],
];
```

Run the following command after picking your `xssless.default` config:

```shell
php artisan xssless:setup
```

## Usage

Using `Medilies\Xssless\Dompurify\DompurifyCliConfig`:

```php
$config = new Medilies\Xssless\Dompurify\DompurifyCliConfig('node', 'npm');

(new Medilies\Xssless\Xssless)
    ->using($config)
    ->clean($html);
```

Using `Medilies\Xssless\Dompurify\DompurifyServiceConfig`:

```php
$config = new Medilies\Xssless\Dompurify\DompurifyServiceConfig('node', 'npm', '127.0.0.1', 63000);

$xssless = (new Medilies\Xssless\Xssless)
    ->using($config);

/**
 * It is better to have this part in a separate script that runs continuously
 * and independently from your app that manages the HTTP requests or CLI input
 */
$xssless->start();

$xssless->clean($html);
```

### Laravel usage

Using `Medilies\Xssless\Dompurify\DompurifyCliConfig`:

```php
Medilies\Xssless\Laravel\Facades\Xssless::clean($html);
```

Using `Medilies\Xssless\Dompurify\DompurifyServiceConfig`:

```shell
php artisan xssless:start
```

```php
Medilies\Xssless\Laravel\Facades\Xssless::clean($html);
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Testing

```bash
./vendor/bin/pest
```

### Formatting

```bash
./vendor/bin/pint
```

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [medilies](https://github.com/medilies)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
