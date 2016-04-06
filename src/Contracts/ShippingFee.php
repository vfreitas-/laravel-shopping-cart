<?php

namespace ShoppingCart\Contracts;

interface ShippingFee
{
    /**
     * Constructor
     */
    public function __construct();

    /**
     * Get the shipping fee value.
     *
     * @return double
     */
    public function getShippingFee();
}
