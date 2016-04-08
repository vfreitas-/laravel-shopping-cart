<?php

namespace ShoppingCart;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;

use ShoppingCart\Contracts\Store;
use ShoppingCart\Contracts\ShoppingCartItem;

use ShoppingCart\Traits\FindInCollectionTrait;

class CartStore extends Store
{
    use FindInCollectionTrait;

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

    /**
     * Sum price from cart items
     * @param $field
     * @return double
     */
    public function sum($field = null)
    {
        if (is_null($field)) {
            return $this->get()->sum(
                function ($item) {
                    return $item->getPrice();
                }
            );
        } else {
            return $this->get()->sum($field);
        }
    }

    /**
     * @return Collection
     */
    public function getGrouped(Collection $items = null)
    {
        if (is_null($items)) {
            $items = $this->get()->groupBy(
                function ($item) {
                    return $item->getIdentifier();
                }
            );
        }

        $groupedItems = collect();

        foreach ($items as $item) {
            $t = $item->first();
            $t->spc_quantity = $item->count();
            $groupedItems->push($t);
        }

        return $groupedItems;
    }
}
