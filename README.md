# The dompdf support for bow framework

Ce package vous permettra de manipuler `dompdf/dompdf` via un _wrapper_ simple et intuitif. <a href="https://travis-ci.org/papac/bow-dompdf" title="Travis branch"><img src="https://img.shields.io/travis/papac/bow-dompdf/master.svg?style=flat-square"/></a>

## Usage

Installez une copie du package avec [composer](https://getcomposer.org).

### Installation

```bash
composer require bowphp/pdf
```

### Configuration

Dans le fichier `app\Kernel.php`. Ajoutez le service comme suit:

```php
/**
 * All app services register
 *
 * @return array
 */
public function configurations()
{
  /**
   * Put here you service
   */
  return [
  	\Bow\PDFConfiguration::class,
      // other
  ];
}
```

## Contributing

Thank you for considering contributing to Bow Framework! The contribution guide is in the framework documentation.

- [Franck DAKIA](https://github.com/papac)
- [Thank's collaborators](https://github.com/bowphp/pdf/graphs/contributors)

## Contact

[papac@bowphp.com](mailto:papac@bowphp.com) - [@franck_dakia](https://twitter.com/franck_dakia)

**Please, if there is a bug on the project please contact me by email or leave me a message on the [slack](https://bowphp.slack.com). or [join us on slask](https://join.slack.com/t/bowphp/shared_invite/enQtNzMxOTQ0MTM2ODM5LTQ3MWQ3Mzc1NDFiNDYxMTAyNzBkNDJlMTgwNDJjM2QyMzA2YTk4NDYyN2NiMzM0YTZmNjU1YjBhNmJjZThiM2Q)**