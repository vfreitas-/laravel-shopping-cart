<?php

use ShoppingCart\Contracts\ShippingFee;

class ShippingFeeHandler implements ShippingFee
{
    /**
     * Constructor
     */
    public function __construct()
    {
        
    }

    public function getShippingFee()
    {
        return 20;
    }
}
