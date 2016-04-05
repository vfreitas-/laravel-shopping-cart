<?php

namespace ShoppingCart\Traits;

trait InstanceTrait
{

	public $configName = 'shopping-cart';

	public function getInstance($key)
	{
		$clazz = config("{$this->configName}.{$key}");
		return new $clazz;
	}
}
