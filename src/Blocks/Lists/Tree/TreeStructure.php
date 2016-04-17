<?php

namespace Kisphp\Blocks\Lists\Tree;

class TreeStructure
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
     * @return string
     */
    public function parse()
    {
        $html = '<ul>';
        /** @var Item $item */
        foreach ($this->items as $item) {
            $html .= $item->parse();
        }
        $html .= '</ul>';

        return $html;
    }
}
