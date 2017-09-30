# Yii2 Tempmail Validator

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Total Downloads][ico-downloads]][link-downloads]

Yii2 validator, based on [daveearley/Email-Validation-Tool](https://github.com/daveearley/Email-Validation-Tool)
to protect your site from users, who use 10-minutes mail services.

## Installation

Install the latest version with

```sh
$ composer require ely/yii2-tempmail-validator
```

## Usage

Once the extension is installed, simply use it in your models:

```php
public function rules()
{
    return [
        [['email'], \Ely\Yii2\TempmailValidator::class],
    ];
}
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

This package was designed and developed within the [Ely.by](http://ely.by) project team. We also thank all the
[contributors](link-contributors) for their help.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/ely/yii2-tempmail-validator.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/ely/yii2-tempmail-validator.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/ely/yii2-tempmail-validator
[link-author]: https://github.com/ErickSkrauch
[link-contributors]: ../../contributors
[link-downloads]: https://packagist.org/packages/ely/yii2-tempmail-validator
