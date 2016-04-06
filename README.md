# laravel-shopping-cart
A flexible and lightweight Shopping Cart manager.

## Installation
### 1 - Dependency
The first step is using composer to install the package and automatically update your `composer.json` file, you can do this by running:
```shell
not released yet
```

### 2 - Provider
You need to update your application configuration in order to register the package so it can be loaded by Laravel, just update your `config/app.php` file adding the following code at the end of your `'providers'` section:

> `config/app.php`

```php
//...
    'providers' => [
        // other providers ommited
        ShoppingCart\Providers\ShoppingCartServiceProvider::class,
    ],
//...
```

### 3 - Facade

In order to use the `ShoppingCart` facade, you need to register it on the `config/app.php` file, you can do that the following way:

```php
//...
    'aliases' => [
        // other Facades ommited
        'ShoppingCart'   => ShoppingCart\Facades\ShoppingCart::class
    ],
//...
```


### 4 - Configuration

#### Publish config

In your terminal type
```shell
php artisan vendor:publish
```
or
```shell
php artisan vendor:publish --provider="ShoppingCart\Providers\ShoppingCartServiceProvider"
```

### 5 - Usage

### 6 - Tests and Codesniffer(PSR2)

To run tests
```shell
phpunit
```

To run the codesniffer coverage
```shell
composer cs
```
or
```shell
composer run-script cs
```

