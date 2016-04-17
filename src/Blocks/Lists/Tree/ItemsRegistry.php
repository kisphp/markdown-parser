<?php

namespace Kisphp\Blocks\Lists\Tree;

class ItemsRegistry
{
    /**
     * @var array
     */
    protected $items = [];

    /**
     * @param Item $item
     */
    public function addItem(Item $item)
    {
        $this->items[$item->getId()] = $item;
    }

    /**
     * @param int $id
     *
     * @return Item|null
     */
    public function getItemById($id)
    {
        if ($id < 0 || !isset($this->items[$id])) {
            return null;
        }

        return $this->items[$id];
    }
}
