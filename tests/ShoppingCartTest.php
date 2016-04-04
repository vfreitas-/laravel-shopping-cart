<?php

use Illuminate\Support\Facades\Session;

class ShoppingCartTest extends TestCase
{

    public function test_sanity()
    {
        Session::set('cart', 'cart in session!');

        $this->assertEquals(Session::get('cart'), 'cart in session!');
    }
}
