<?php

use Illuminate\Support\Facades\Session;
use Illuminate\Config\Repository as Config;

class ServiceProvider extends TestCase
{
    public function test_if_the_service_provider_is_registered()
    {
        $provider = $this->app
            ->getProvider('ShoppingCart\Providers\ShoppingCartServiceProvider');

        $this->assertNotEquals($provider, null);
    }

    public function test_if_shopping_cart_is_on_the_IOC_container()
    {
        $shoppingCart = $this->app->make('shopping_cart');

        $this->assertInstanceOf('ShoppingCart\ShoppingCart', $shoppingCart);
    }

    public function test_if_config_exists()
    {
        $value = $this->app->config->get('shopping-cart');

        $this->assertInternalType('array', $value);
    }
}
