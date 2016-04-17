<?php

namespace Kisphp\Blocks\Lists;

use Kisphp\BlockInterface;

/**
 * @deprecated not used
 */
class ListTree
{
    /**
     * @var int
     */
    protected $currentLevel = 0;

    /**
     * @var ListTree[]
     */
    protected $items = [];

    /**
     * @var array
     */
    protected $itemsPosition = [];

    /**
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param ListTreeItem $tree
     *
     * @return $this
     */
    public function addItem(ListTreeItem $tree)
    {
        $this->items[] = $tree;

        return $this;
    }

    /**
     * @param BlockInterface $listContent
     */
    public function createItem(BlockInterface $listContent)
    {
        $treeItem = new ListTreeItem();
        $treeItem->setContent($listContent->getContent());

        $newLevel = $this->getLevelByContent($listContent->getContent());
        $treeItem->setLevel($newLevel);

        if ($newLevel !== $this->currentLevel) {
            //} && $newLevel > $this->currentLevel) {
            $prev = $this->itemsPosition[$this->currentLevel];
            dump($prev);
            dump($this->itemsPosition);
            $this->items[$prev]->addChildren($treeItem);

            $this->currentLevel = $newLevel;
        } else {
            $this->addItem($treeItem);
        }

        $this->itemsPosition[$this->currentLevel] = $listContent->getLineNumber();
    }

    /**
     * @param string $lineContent
     *
     * @return int
     */
    protected function getLevelByContent($lineContent)
    {
        // transform tabs to spaces
        $lineContent = str_replace("\t", '    ', $lineContent);

        preg_match("/\S/", $lineContent, $spacesFound, PREG_OFFSET_CAPTURE);

        $levelDelimiter = '    ';

        $length = max(1, $spacesFound[0][1]);
        $foundLevel = substr_count($lineContent, $levelDelimiter, 0, $length);

        if ($foundLevel > $this->currentLevel) {
            $newLevel = min($this->currentLevel + 1, $foundLevel);

            return $newLevel;
        }

        if ($foundLevel < $this->currentLevel) {
            return $foundLevel;
        }

        return $this->currentLevel;
    }

    /**
     * @return string
     */
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
