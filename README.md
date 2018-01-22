## The dompdf support for bow framework

Ce package vous permettra de manipuler `dompdf/dompdf` via un wrapper simple et intuitif.

## Usage

Installez une copie du package avec [composer](https://getcomposer.org).

### Install
```
composer require papac/bow-dompdf
```

### Configuration

Vous pouvez utiliser directement le service fournir dans le package.

Dans le fichier `app/Kernel/Loader.php` du dossier `config`. Ajoutez le service comme suit:


```php
/**
 * All app services register
 *
 * @return array
 */
public function services()
{
    /**
     * Mettez ici vos service.
     */
    return [
        \Papac\PDFService::class,
        // other
    ];
}
```

## Author

Dakia Franck



