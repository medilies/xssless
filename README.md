# Sanitize your HTML against XSS threats.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/medilies/xssless.svg?style=flat-square)](https://packagist.org/packages/medilies/xssless)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/medilies/xssless/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/medilies/xssless/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/medilies/xssless/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/medilies/xssless/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/medilies/xssless.svg?style=flat-square)](https://packagist.org/packages/medilies/xssless)

...
- https://cheatsheetseries.owasp.org/cheatsheets/Cross_Site_Scripting_Prevention_Cheat_Sheet.html#html-sanitization

## Installation

You can install the package via composer:

```bash
composer require medilies/xssless
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="xssless-config"
```

This is the contents of the published config file:

```php
return [
];
```

## Usage

```php
$xssless = new Medilies\Xssless();
```

## Testing

```bash
./vendor/bin/pest
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [medilies](https://github.com/medilies)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
