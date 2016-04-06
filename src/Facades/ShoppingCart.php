<?php

namespace ShoppingCart\Facades;

use Illuminate\Support\Facades\Facade;

class ShoppingCart extends Facade
{
    /**
     * Get the binding in the IoC container
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'shopping_cart';
    }
}
