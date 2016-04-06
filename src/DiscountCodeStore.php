<?php

namespace ShoppingCart;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;

use ShoppingCart\Contracts\Store;
use ShoppingCart\Contracts\ShoppingCartItem;

/**
 * Class DiscountCodeStore
 *
 * @package ShoppingCart
 */
class DiscountCodeStore extends Store
{
    /**
     * @param  ShoppingCartItem $item
     * @return ShoppingCartItem
     */
    public function discount(ShoppingCartItem $item)
    {
        $final_price = $this->verifyDiscount($item);
        $item->old_final_price = $item->final_price;
        $item->final_price = $final_price;

        return $item;
    }

    /**
     * @param Collection $items
     * @return mixed
     */
    public function registerDiscountCode(Collection $items)
    {
        return $items->map(
            function ($item) {
                $final_price = $this->verifyDiscount($item);
                $item->old_final_price = $item->final_price;
                $item->final_price = $final_price;
                return $item;
            }
        );
    }

    /**
     * @param  Collection $items
     * @return mixed
     */
    public function removeDiscountCode(Collection $items)
    {
        $this->clear();
        $this->set();

        return $items->map(
            function ($item) {
                $item->final_price = $item->old_final_price;
                return $item;
            }
        );
    }

    /**
     * @param  ShoppingCartItem $item
     * @return double
     */
    private function verifyDiscount(ShoppingCartItem $item)
    {
        $discountCode = $this->get();

        if (empty($discountCode)) {
            return $item->final_price;
        }

        $code_pct = floatval($discountCode->discount_pct);

        if ($code_pct >= $item->discount_rate) {
            $rate = ($code_pct / 100);
            $discount = $item->original_price * $rate;
            return $item->original_price - $discount;
        }

        return $item->final_price;
    }
}
