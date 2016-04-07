<?php

namespace ShoppingCart\Traits;

trait InstanceTrait
{

    public $configName = 'shopping-cart';

    public function getInstance($key)
    {
        $value = config("{$this->configName}.{$key}");

        //list($class, $method) = explode('@', $value);
//        dd(is_string($clazz));
        if (!is_null($value)) {
            return new $value;
        }
    }
}
