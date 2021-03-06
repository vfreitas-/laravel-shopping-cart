<?php

use ShoppingCart\Contracts\ShoppingCartItem;

class ProductStub implements ShoppingCartItem
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
        return $this->id;
    }

    public function getPrice()
    {
        return $this->price;
    }
}
