<?php

namespace ShoppingCart\Providers;

use Illuminate\Support\ServiceProvider;

use ShoppingCart\ShoppingCart;

class ShoppingCartServiceProvider extends ServiceProvider
{
    /**
     * @var string
     */
    protected $configFile = __DIR__.'/../../resources/config/shopping-cart.php';

    /**
    * Register any application services.
    *
    * @return void
    */
    public function register()
    {
        $this->app->singleton('shopping_cart', function () {
            return new ShoppingCart();
        });
    }

    /**
    * Perform post-registration booting of services.
    *
    * @return void
    */
    public function boot()
    {
        $this->publishes([
            $this->configFile => config_path('shopping-cart.php'),
        ]);

        $this->mergeConfigFrom(
            $this->configFile, 'shopping-cart'
        );
    }

}
