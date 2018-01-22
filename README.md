## The dompdf support for bow framework

Ce package vous permettra de manipuler `dompdf/dompdf` via un _wrapper_ simple et intuitif.

## Usage

Installez une copie du package avec [composer](https://getcomposer.org).

### Installation
```
composer require papac/bow-dompdf
```

### Configuration

Dans le fichier `Loader.php` du dossier `app/Kernel`. Ajoutez le service comme suit:


```php
/**
 * All app services register
 *
 * @return array
 */
public function services()
{
    /**
     * Put here you service
     */
    return [
        \Papac\PDFService::class,
        // other
    ];
}
```

## Auteur

Dakia Franck