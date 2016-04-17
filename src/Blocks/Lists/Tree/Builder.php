<?php

namespace Kisphp\Blocks\Lists\Tree;

use Kisphp\BlockInterface;

class Builder
{
    /**
     * @var TreeStructure
     */
    protected $treeStructure;

    /**
     * @var ItemsRegistry
     */
    protected $itemsRegistry;

    /**
     * @var array
     */
    protected $levelLastItem = [];

    public function __construct()
    {
        $this->treeStructure = new TreeStructure();
        $this->itemsRegistry = new ItemsRegistry();
    }

    /**
     * @param BlockInterface $block
     */
    public function addItem(BlockInterface $block)
    {
        $lineContent = $block->getContent();

        $item = new Item();
        $item->setContent($lineContent);
        $item->setId($block->getLineNumber());
        $level = $this->getLevelByContent($lineContent);
        $item->setLevel($level);

        $this->itemsRegistry->addItem($item);

        $this->addToTreeSTructure($item, $level);

        $this->levelLastItem[$level] = $item->getId();
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

        return $foundLevel;

//        if ($foundLevel > $this->currentLevel) {
//            $newLevel = min($this->currentLevel + 1, $foundLevel);
//
//            return $newLevel;
//        }
//
//        if ($foundLevel < $this->currentLevel) {
//
//            return max(0, $foundLevel);
//        }
//
//        return $this->currentLevel;
    }

    /**
     * @return TreeStructure
     */
    public function getTreeStructure()
    {
        return $this->treeStructure;
    }

    /**
     * @param Item $item
     * @param int $level
     */
    protected function addToTreeSTructure(Item $item, $level)
    {
        if ($level > 0) {
            $previousItemId = $this->levelLastItem[$level - 1];
            /** @var Item $previousItem */
            $previousItem = $this->itemsRegistry->getItemById($previousItemId);

            if ($previousItem) {
                $previousItem->addClild($item);
            }

            return;
        }

        $this->getTreeStructure()->addItem($item);
    }
}
