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

    /**
     *	Get the shipping fee value.
     *	@return double
     */
    public function getShippingFee()
    {
        return 40;
    }
}
