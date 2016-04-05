<?php

use Illuminate\Support\Facades\Session;
use Illuminate\Config\Repository as Config;

class ShoppingCartTest extends TestCase
{
    protected $shoppingCart;

    public function test_if_cart_has_shipping_fee_value()
    {
        $fee = $this->shoppingCart->getShippingFee();

        $this->assertEquals($fee, 20);
    }

    public function test_if_a_product_can_be_added_to_cart()
    {
        $product = new ProductStub();

        $cart = $this->shoppingCart->add($product);

        $this->assertEquals($cart->get('items')->count(), 1);
    }

    /**
     * Setup DB before each test.
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->shoppingCart = $this->app->make('shopping_cart');
    }
}
