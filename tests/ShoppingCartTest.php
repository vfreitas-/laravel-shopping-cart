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

        $cart = $this->shoppingCart->addProduct($product);

        $this->assertEquals(
            $cart->get('items')->count(),
            1
        );
    }

    public function test_should_add_an_array_of_products_to_cart()
    {
        $product1 = new ProductStub();
        $product2 = new ProductStub();

        $products = [$product1, $product2];

        $cart = $this->shoppingCart->addProducts($products);

        $this->assertEquals(
            $cart->get('items')->count(),
            2
        );
    }

    public function test_cart_should_remove_a_product()
    {
        $product = new ProductStub();

        $this->shoppingCart->addProduct($product);

        $cart = $this->shoppingCart->removeProduct($product->id);

        $this->assertTrue(
            $cart->get('items')->isEmpty()
        );
    }

    public function test_cart_should_decrease_a_product_quantity()
    {
        $product = new ProductStub();

        $this->shoppingCart->addProduct($product);
        $this->shoppingCart->addProduct($product);
        $this->shoppingCart->addProduct($product);

        $cart = $this->shoppingCart->decreaseProductQnt($product->id, 2);

        $this->assertEquals(
            $cart->get('items')->count(),
            1
        );
    }

    public function test_cart_should_replace_a_product_()
    {
        $product1 = new ProductStub();
        $product2 = new ProductStub();

        $this->shoppingCart->addProduct($product1);

        $cart = $this->shoppingCart->replaceProduct($product1->id, $product2);

        $this->assertEquals(
            $cart->get('items')[0]->id,
            $product2->id
        );
    }

    public function test_cart_should_sum_products_price()
    {
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    public function test_cart_should_sum_products_field()
    {
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    public function test_cart_should_return_shipping_fee_value_from_passed_implementation()
    {
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
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
