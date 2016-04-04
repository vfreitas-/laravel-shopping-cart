<?php

namespace ShoppingCart;

use Illuminate\Support\Collection;

/**
 * Class DiscountCodeService
 * @package App\Services\DiscountCode
 */
class DiscountCodeService
{
    /**
     * @var string
     */
    protected $sessionName = 'discount_code';

    /**
     *
     */
    public function __construct()
    {
        if(!session()->has($this->sessionName))
        session()->put($this->sessionName, null);
    }

    /**
     * @param DiscountCode $discountCode
     */
    public function set(DiscountCode $discountCode = null)
    {
        session()->put($this->sessionName, $discountCode);
    }

    /**
     * @return mixed
     */
    public function get()
    {
        return session()->get($this->sessionName);
    }

    /**
     * @return boolean
     */
    public function has()
    {
        return session()->has($this->sessionName);
    }

    /**
     *
     */
    public function clear()
    {
        session()->forget($this->sessionName);
    }

    /**
     * @param  ProductVariation $product
     * @return ProductVariation
     */
    public function discount(ProductVariation $product)
    {
        $final_price = $this->verifyDiscount($product);
        $product->old_final_price = $product->final_price;
        $product->final_price = $final_price;

        return $product;
    }

    /**
     * @param Collection $items
     * @return mixed
     */
    public function registerDiscountCode(Collection $items)
    {
        return $items->map(function($item) {
            $final_price = $this->verifyDiscount($item);
            $item->old_final_price = $item->final_price;
            $item->final_price = $final_price;
            return $item;
        });
    }

    /**
     * @param  Collection $items
     * @return mixed
     */
    public function removeDiscountCode(Collection $items)
    {
        $this->clear();
        $this->set();

        return $items->map(function($item) {
            $item->final_price = $item->old_final_price;
            return $item;
        });
    }

    /**
     * @param  ProductVariation $product
     * @return double final_price
     */
    private function verifyDiscount(ProductVariation $product)
    {
        $discountCode = $this->get();
        if(empty($discountCode)) return $product->final_price;

        $code_pct = floatval($discountCode->discount_pct);

        if($code_pct >= $product->discount_rate) {
            $rate = ($code_pct / 100);
            $discount = $product->original_price * $rate;
            return $product->original_price - $discount;
        }

        return $product->final_price;
    }
}
