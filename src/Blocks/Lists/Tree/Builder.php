<?php

namespace Kisphp\Blocks\Lists\Tree;

use Kisphp\BlockInterface;

class Builder
{
    const SPACES_AS_TAB = 4;
    const TAB_CHARACTER = "\t";
    const SPACE_CHARACTER = ' ';

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
        $this->treeStructure = $this->createTreeStructure();
        $this->itemsRegistry = $this->createItemsRegistry();
    }

    /**
     * @return TreeStructure
     */
    protected function createTreeStructure()
    {
        return new TreeStructure($this);
    }

    /**
     * @return ItemsRegistry
     */
    protected function createItemsRegistry()
    {
        return new ItemsRegistry();
    }

    /**
     * @return Item
     */
    protected function createItem()
    {
        return new Item($this);
    }

    /**
     * @param Item $item
     *
     * @return string
     */
    public function createListStartTag(Item $item)
    {
        return '<' . $item->getListType() . '>' . "\n";
    }

    /**
     * @param Item $item
     *
     * @return string
     */
    public function createListEndTag(Item $item)
    {
        return '</' . $item->getListType() . '>' . "\n";
    }

    /**
     * @return string
     */
    public function createListItemStartTag()
    {
        return '<li>';
    }

    /**
     * @return string
     */
    public function createListItemEndTag()
    {
        return '</li>' . "\n";
    }

    /**
     * @param BlockInterface $block
     */
    public function addItem(BlockInterface $block)
    {
        $lineContent = $block->getContent();

        $item = $this->createItem();
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
        $levelDelimiter = str_repeat(static::SPACE_CHARACTER, static::SPACES_AS_TAB);

        $lineContent = str_replace(static::TAB_CHARACTER, $levelDelimiter, $lineContent);

        preg_match("/\S/", $lineContent, $spacesFound, PREG_OFFSET_CAPTURE);

        $length = max(1, $spacesFound[0][1]);
        $foundLevel = substr_count($lineContent, $levelDelimiter, 0, $length);

        return $foundLevel;
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
