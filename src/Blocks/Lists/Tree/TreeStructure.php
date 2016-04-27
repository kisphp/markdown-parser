<?php

namespace Kisphp\Blocks\Lists\Tree;

class TreeStructure implements TreeStructureInterface
{
    /**
     * @var array
     */
    protected $items = [];

    /**
     * @var BuilderInterface
     */
    protected $builder;

    /**
     * @param BuilderInterface $builder
     */
    public function __construct(BuilderInterface $builder)
    {
        $this->builder = $builder;
    }

    /**
     * @param ItemInterface $item
     */
    public function addItem(ItemInterface $item)
    {
        $this->items[$item->getId()] = $item;
    }

    /**
     * @return ItemInterface
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
            /** @var ItemInterface $firstChild */
            $firstChild = $this->getFirstChild();
            $html .= $this->builder->createListStartTag($firstChild);

            /** @var ItemInterface $item */
            foreach ($this->items as $item) {
                $html .= $item->parse();
            }

            $html .= $this->builder->createListEndTag($firstChild);
        }

        return $html;
    }
}
