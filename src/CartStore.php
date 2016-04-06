<?php

namespace ShoppingCart;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;

use ShoppingCart\Contracts\Store;
use ShoppingCart\Contracts\ShoppingCartItem;

class CartStore extends Store
{
    /**
     * @param ShoppingCartItem $item
     * @return void
     */
    public function add(ShoppingCartItem $item)
    {
        if (!Session::has($this->sessionName)) {
            Session::put($this->sessionName, collect([$item]));
        } else {
            Session::push($this->sessionName, $item);
        }
    }

    /**
     * @param mixed $identifier
     * @return void
     */
    public function remove($identifier)
    {
        $items = $this->get();

        $filtered = $items->filter(
            function ($item) use ($identifier) {
                return $item->getIdentifier() !== $identifier;
            }
        );

        $this->set($filtered);
    }

    /**
     * @param mixed $identifier
     * @return void
     */
    public function decreaseQuantity($identifier)
    {
        $items = $this->get();

        $product = $items->filter(
            function ($item) use ($identifier) {
                return $item->getIdentifier() === $identifier;
            }
        )->keys()[0];

        $filtered = $items->except([$product]);

        $this->set($filtered);
    }

    /**
     * @param mixed            $identifier
     * @param ShoppingCartItem $item
     * @return void
     */
    public function replaceItem($identifier, ShoppingCartItem $newItem)
    {
        $items = $this->get();

        $filtered = $items->map(
            function ($item) use ($identifier, $newItem) {
                return $item->getIdentifier() === $identifier
                    ? $newItem
                    : $item;
            }
        );

        $this->set($filtered);
    }
}
