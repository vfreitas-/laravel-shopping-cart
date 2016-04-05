<?php

namespace ShoppingCart;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Illuminate\Config\Repository as Config;

use ShoppingCart\Traits\InstanceTrait;
use ShoppingCart\Contracts\ShippingFee;
use ShoppingCart\Contracts\ShoppingCartItem;

/**
* Class ShoppingCart
* @package ShoppingCart
*/
class ShoppingCart
{
    use InstanceTrait;

    /**
     * @var string
     */
    protected $sessionName;

    /**
     *
     */
    public function __construct()
    {
        $this->sessionName = Config::get('shopping-cart.session_key');

        if(!Session::has($this->sessionName))
            Session::put($this->sessionName, collect());
    }

    /**
     * @param Collection $items
     */
    public function set(Collection $items)
    {
        Session::put( $this->sessionName, $items );
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
     * @param ProductVariation $product
     * @return Collection
     */
    public function add(ShoppingCartItem $item)
    {
        Session::push($this->sessionName, $item);
        return $this->getWithValue();
    }

    /**
     * @param $productSku
     * @return mixed
     */
    public function remove($productSku)
    {
        $items = $this->get();

        $filtered = $items->filter(function ($item) use($productSku) {
            return array_get($item, 'sku') != $productSku;
        });

        $this->set($filtered);

        return $this->getWithValue();
    }

    /**
     * @param $productSku
     * @return mixed
     */
    public function decreaseQuantity($productSku)
    {

        $items = $this->get();

        $product = $items->filter(function($item) use($productSku) {
            return array_get($item, 'sku') == $productSku;
        })->keys()[0];

        $filtered = $items->except([$product]);

        $this->set($filtered);

        return $this->getWithValue();
    }

    /**
     * @return mixed
     */
    public function replaceItem()
    {
        $items = $this->get();

        $filtered = $item->map(function($item, $key) use($oldProductSku, $product) {
            return $item;
            //return array_get($item, 'sku') === $oldProductSku ? $product : $item;
        });

        $this->set($filtered);

        return $this->getWithValue();
    }

    /**
     * @return mixed
     *
     */
    public function count()
    {
        return $this->get()->count();
    }

    /**
     * @return mixed
     *
     */
    public function isEmpty()
    {
        return $this->get()->isEmpty();
    }

    /**
     * @return mixed
     */
    public function sum($field = null)
    {
        if(is_null($field))
            return $this->get()->sum(function($item) {
                $item->getPrice();
            });
        else
            return $this->get()->sum($field);
    }

    /**
     * @return Collection
     */
    public function getGrouped()
    {
        $items = $this->get()->groupBy(function($item) {
            return $item->getIdentifier();
        });

        $groupedItems = collect();

        foreach($items as $item)
        {
            $pr = $item->first();
            $pr->quantity = $item->count();
            $groupedItems->push($pr);
        }

        return $groupedItems;
    }

    /**
     * @return Collection
     */
    public function getWithValue()
    {
        return collect([
            'items' => $this->getGrouped(),
            'total' => $this->sum(),
            'frete' => $this->getShippingFee(),
            'count' => $this->count(),
            'totalPurchase' => $this->getTotalPurchaseValue()
        ]);
    }

    /**
     * @return mixed
     */
    public function getTotalPurchaseValue()
    {
        return $this->sum('final_price') + $this->getShippingFee();
    }
}
