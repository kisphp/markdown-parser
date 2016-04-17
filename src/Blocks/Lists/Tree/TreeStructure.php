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
     * @return Item
     */
    protected function getFirstChild()
    {
        return reset($this->items);
    }

    /**
     * @return string
     */
    public function parse()
    {
        $html = '';

        if (count($this->items) > 0) {
            /** @var Item $firstChild */
            $firstChild = $this->getFirstChild();
            $html .= '<' . $firstChild->getListType() . '>' . "\n";
            /** @var Item $item */
            foreach ($this->items as $item) {
                $html .= $item->parse();
            }
            $html .= '</' . $firstChild->getListType() . '>' . "\n";
        }

        return $html;
    }
}
