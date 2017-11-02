# concrete5 fake data

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.txt)
[![Total Downloads][ico-downloads]][link-downloads]

## Currently supported commands
You can create fake data via the CLI. Start the CLI environment via `concrete/bin/concrete5`. Supported commands are:
- `fake-data:create:logs --amount 100`

## Installation Composer based environment

To install this package on a composer based concrete5 site, make sure you already have composer/installers then run:

```sh
$ composer require a3020/fake_data
```

Then install the package:

```sh
$ ./vendor/bin/concrete5 c5:package-install fake_data
```

## Installation normal environment
In a normal concrete5 environment, download the ZIP file, extract its
contents to `packages` and run `composer install` in the `packages/fake_data` directory.

[ico-version]: https://img.shields.io/packagist/v/a3020/fake_data.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/a3020/fake_data.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/a3020/fake_data
[link-downloads]: https://packagist.org/packages/a3020/fake_data
