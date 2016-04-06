<?php

namespace ShoppingCart\Contracts;

use Illuminate\Support\Collection;
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
    public function __construct($sessionName = null)
    {
        $this->sessionName = $sessionName;
    }

    /**
     * @param Collection $value
     * @return void
     */
    public function set(Collection $value)
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
     * @return void
     */
    public function clear()
    {
        Session::forget($this->sessionName);
    }

    /**
     * @return void
     */
    public function has()
    {
        Session::has($this->sessionName);
    }
}
