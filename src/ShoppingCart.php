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
     * @param ShoppingCartItem $item
     * @return Collection
     */
    public function addProduct(ShoppingCartItem $item)
    {
        $this->cartStore->add($item);
        return $this->getCartDetail();
    }

    /**
     * @param array $items
     * @return Collection
     */
    public function addProducts(array $items)
    {
        foreach ($items as $item) {
            $this->cartStore->add($item);
        }

        return $this->getCartDetail();
    }

    /**
     * @param $identifier
     * @return mixed
     */
    public function removeProduct($identifier)
    {
        $this->cartStore->remove($identifier);
        return $this->getCartDetail();
    }

    /**
     * @param $identifier
     * @return mixed
     */
    public function decreaseProductQnt($identifier, $quantity = 1)
    {
        foreach (range(1, $quantity) as $i) {
            $this->cartStore->decreaseQuantity($identifier);
        }

        return $this->getCartDetail();
    }

    /**
     * @param $identifier
     * @param ShoppingCartItem $item
     * @return mixed
     */
    public function replaceProduct($identifier, ShoppingCartItem $item)
    {
        $this->cartStore->replaceItem($identifier, $item);
        return $this->getCartDetail();
    }

    /**
     * @return mixed
     */
    public function clearCart()
    {
        $this->cartStore->clear();
        return $this->getCartDetail();
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
        return $this->cartStore->sum($field);
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
            $t = $item->first();
            $t->spc_quantity = $item->count();
            $groupedItems->push($t);
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
    public function getCartDetail()
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
        return $this->sum() + $this->getShippingFee();
    }
}
