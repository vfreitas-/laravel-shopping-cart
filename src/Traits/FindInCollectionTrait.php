<?php

namespace ShoppingCart\Traits;

use Illuminate\Support\Collection;

trait FindInCollectionTrait
{
    /**
     * @param string $field
     * @param any $value
     * @param bool $first
     * @return ShoppingCartItem
     */
    public function findByField($field, $value, array $options = [])
    {
        $result = $this->get()->where($field, $value);
        return $this->findReturnParser($result, $options);
    }

    /**
     * @param array $where
     * @param bool $first
     * @return ShoppingCartItem
     */
    public function findWhere($where, $first, array $options = [])
    {
        $items = $this->get();

        foreach ($where as $field => $value) {
            if (is_array($value)) {
                list($condition, $val) = $value;
                $model = $model->where($field, $condition, $val);
            } else {
                if (is_null($value)) {
                    $model = $model->whereNull($field);
                }

                $model = $model->where($field, '=', $value);
            }
        }
    }

    /**
     * @param  Collection $result
     * @param  array  $options
     * @return Collection||ShoppingCartItem
     */
    protected function findReturnParser(Collection $result, array $options)
    {
        $isGrouped = array_get($options, 'grouped');

        return $isGrouped
            ? $this->getGrouped($result)
            : $result;
    }
}
