<?php

namespace Kisphp\Blocks\Lists;

class ListTree
{
    /**
     * @var int
     */
    protected $level = 0;

    /**
     * @var ListTreeInterface[]
     */
    protected $items = [];

    /**
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param ListTreeInterface $treeInterface
     *
     * @return $this
     */
    public function addItem(ListTreeInterface $treeInterface)
    {
        $this->items[] = $treeInterface;

        return $this;
    }

    /**
     * @param string $listContent
     */
    public function createItem($listContent)
    {
        $treeItem = new ListTreeItem();
        $treeItem->setContent($listContent);
        $treeItem->setLevel($this->level);

        $this->addItem($treeItem);
    }

    public function parse()
    {
        $html = '';
        /** @var ListTreeItem $firstElement */
        $firstElement = $this->items[0];

        $html .= $firstElement->getStartTag();
        /** @var ListTreeItem $item */
        foreach ($this->items as $item) {
            $html .= $item->parse();
        }

        $html .= $firstElement->getEndTag();

        return $html;
    }
}
