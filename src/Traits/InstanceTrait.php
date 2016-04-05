<?php

use Illuminate\Config\Repository as Config;

namespace ShoppingCart\Traits;

trait InstanceTrait
{

	public $configName = 'shopping-cart';

	public function getInstance($key)
	{
		$clazz = Config::get($key);
		return new $clazz;
	}
}
