<?php

namespace ShoppingCart\Contracts;

interface ShoppingCartItem
{
    /**
     * Get the entity identifier value
     * Ex. product sku, id
     *
     * @return string/int
     */
    public function getIdentifier();

    /**
     * Get the entity price value
     *
     * @return double
     */
    public function getPrice();
}
