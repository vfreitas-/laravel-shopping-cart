<?php

namespace ShoppingCart\Contracts;

abstract class Store
{

    /**
     * @var string
     */
    protected $sessionName;

    /**
     * @param  string $sessionName
     */
    public function __costruct($sessionName)
    {
        $this->sessionName = $sessionName;
    }

    

}
