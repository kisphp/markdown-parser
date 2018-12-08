<?php

namespace Kisphp\Blocks\Lists\Tree;

class ItemsRegistry
{
    /**
     * @var array
     */
    protected $items = [];

    /**
     * @param ItemInterface $item
     */
    public function addItem(ItemInterface $item)
    {
        $this->items[$item->getId()] = $item;
    }

    /**
     * @param int $id
     *
     * @return null|ItemInterface
     */
    public function getItemById($id)
    {
        if ($id < 0 || !isset($this->items[$id])) {
            return null;
        }

        return $this->items[$id];
    }
}
