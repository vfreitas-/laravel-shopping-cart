<?php

namespace ShoppingCart;

use Illuminate\Support\Collection;

use ShoppingCart\CartStore;
use ShoppingCart\DiscountCodeStore;
use ShoppingCart\Traits\InstanceTrait;
use ShoppingCart\Contracts\ShippingFee;
use ShoppingCart\Contracts\ShoppingCartItem;

/**
 * Class ShoppingCart
 *
 * @package ShoppingCart
 */
class ShoppingCart
{
    use InstanceTrait;

    /**
     * @var CartStore
     */
    protected $cartStore;

    /**
     * @var DiscountCodeStore
     */
    protected $discountCodeStore;

    /**
     * @var shippingFeeHandler
     */
    protected $shippingFeeHandler;

    /**
     *
     */
    public function __construct()
    {
        $key = config('shopping-cart.session_key');

        $this->cartStore = new CartStore("{$key}.cart");
        $this->discountCodeStore = new DiscountCodeStore("{$key}.discount_code");

        $this->shippingFeeHandler = $this->getInstance('shipping_fee_class');
    }

    /**
     * @param ProductVariation $product
     * @return Collection
     */
    public function addProduct(ShoppingCartItem $item)
    {
        $this->cartStore->add($item);
        return $this->getWithValue();
    }

    /**
     * @param $identifier
     * @return mixed
     */
    public function removeProduct($identifier)
    {
        $items = $this->cartStore->get();

        $filtered = $items->filter(
            function ($item) use ($identifier) {
                return array_get($item, 'sku') != $identifier;
            }
        );

        $this->set($filtered);

        return $this->getWithValue();
    }

    /**
     * @param $identifier
     * @return mixed
     */
    public function decreaseProductQnt($identifier)
    {
        $items = $this->cartStore->get();

        $product = $items->filter(
            function ($item) use ($identifier) {
                return array_get($item, 'sku') == $identifier;
            }
        )->keys()[0];

        $filtered = $items->except([$product]);

        $this->set($filtered);

        return $this->getWithValue();
    }

    /**
     * @return mixed
     */
    public function replaceProduct($identifier, ShoppingCartItem $item)
    {
        $items = $this->cartStore->get();

        $filtered = $item->map(
            function ($item) use ($identifier, $item) {
                return $item;
                //return array_get($item, 'sku') === $oldProductSku ? $product : $item;
            }
        );

        $this->set($filtered);

        return $this->getWithValue();
    }

    /**
     * @return mixed
     */
    public function count()
    {
        return $this->cartStore->get()->count();
    }

    /**
     * @return mixed
     */
    public function isEmpty()
    {
        return $this->cartStore->get()->isEmpty();
    }

    /**
     * @return mixed
     */
    public function sum($field = null)
    {
        if (is_null($field)) {
            return $this->cartStore->get()->sum(
                function ($item) {
                    $item->getPrice();
                }
            );
        } else {
            return $this->cartStore->get()->sum($field);
        }
    }

    /**
     * @return Collection
     */
    public function getGrouped()
    {
        $items = $this->cartStore->get()->groupBy(
            function ($item) {
                return $item->getIdentifier();
            }
        );

        $groupedItems = collect();

        foreach ($items as $item) {
            $pr = $item->first();
            $pr->quantity = $item->count();
            $groupedItems->push($pr);
        }

        return $groupedItems;
    }

    /**
     * @return double
     */
    public function getShippingFee()
    {
        if ($this->shippingFeeHandler instanceof ShippingFee) {
            return $this->shippingFeeHandler->getShippingFee();
        } else {
            return 0;
        }
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
