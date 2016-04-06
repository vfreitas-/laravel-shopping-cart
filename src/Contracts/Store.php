<?php

namespace ShoppingCart\Contracts;

use Illuminate\Support\Facades\Session;

abstract class Store
{
    /**
     * @var string
     */
    protected $sessionName;

    /**
     * @param  string $sessionName
     */
    protected function __costruct($sessionName = null)
    {
        $this->sessionName = $sessionName;
    }

    /**
     * @param mixed $value
     */
    public function set($value)
    {
        Session::put($this->sessionName, $value);
    }

    /**
     * @return mixed
     */
    public function get()
    {
        return Session::get($this->sessionName);
    }

    /**
     *
     */
    public function clear()
    {
        Session::forget($this->sessionName);
    }

    /**
     *
     */
    public function has()
    {
        Session::has($this->sessionName);
    }
}
