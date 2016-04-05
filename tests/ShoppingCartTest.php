<?php

use Illuminate\Support\Facades\Session;
use Illuminate\Config\Repository as Config;

class ShoppingCartTest extends TestCase
{

    public function test_sanity()
    {
        Session::set('cart', 'cart in session!');

        $this->assertEquals(Session::get('cart'), 'cart in session!');
    }

    public function test_mock()
    {
        $product = new ProductMock;

        $this->assertInstanceOf('ShoppingCart\Contracts\ShoppingCartItem', $product);
    }
}
