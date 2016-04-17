<?php

namespace Kisphp\Blocks\Lists\Tree;

class TreeStructure
{
    /**
     * @var array
     */
    protected $items = [];

    /**
     * @var Builder
     */
    protected $builder;

    /**
     * @param Builder $builder
     */
    public function __construct(Builder $builder)
    {
        $this->builder = $builder;
    }

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
            $html .= $this->builder->createListStartTag($firstChild);
            /** @var Item $item */
            foreach ($this->items as $item) {
                $html .= $item->parse();
            }
            $html .= $this->builder->createListEndTag($firstChild);
        }

        return $html;
    }
}
