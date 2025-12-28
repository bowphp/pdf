# The dompdf support for bow framework

Ce package vous permettra de manipuler `dompdf/dompdf` via un _wrapper_ simple et intuitif.

[![Build Status](https://img.shields.io/travis/bowphp/pdf/main.svg?style=flat-square)](https://travis-ci.org/bowphp/pdf)
![Build Status](https://github.com/bowphp/pdf/actions/workflows/tests.yml/badge.svg)

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
    \Bow\Pdf\PDFConfiguration::class,
      // other
  ];
}
```

## Contributing

Thank you for considering contributing to Bow Framework! The contribution guide is in the framework documentation.

- [Franck DAKIA](https://github.com/papac)
- [Thank's collaborators](https://github.com/bowphp/pdf/graphs/contributors)
