<?php

use ShoppingCart\Contracts\ShoppingCartItem;

class ProductMock implements ShoppingCartItem
{

    public $id;
    public $price;

    public function __construct()
    {
        $faker = Faker\Factory::create();
        $this->id = $faker->numberBetween(1, 20);
        $this->price = $faker->numberBetween(200, 1000);
    }

    public function getIdentifier()
    {
        return $id;
    }

    public function getPrice()
    {
        return $price;
    }
}
