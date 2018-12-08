<?php

namespace Kisphp\Blocks\Lists\Tree;

use Kisphp\BlockInterface;

class Builder implements BuilderInterface
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
     * @param ItemInterface $item
     *
     * @return string
     */
    public function createListStartTag(ItemInterface $item)
    {
        return '<' . $item->getListType() . '>' . "\n";
    }

    /**
     * @param ItemInterface $item
     *
     * @return string
     */
    public function createListEndTag(ItemInterface $item)
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
     *
     * @return ItemInterface
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

        $this->addToTreeStructure($item, $level);

        $this->levelLastItem[$level] = $item->getId();

        return $item;
    }

    /**
     * @return TreeStructure
     */
    public function getTreeStructure()
    {
        return $this->treeStructure;
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
     * @return ItemInterface
     */
    protected function createItem()
    {
        return new Item($this);
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

        $length = $this->getLength($lineContent);
        $foundLevel = substr_count($lineContent, $levelDelimiter, 0, $length);

        return $foundLevel;
    }

    /**
     * @param ItemInterface $item
     * @param int $level
     */
    protected function addToTreeStructure(ItemInterface $item, $level)
    {
        if ($level > 0) {
            $previousItemId = $this->levelLastItem[$level - 1];
            /** @var ItemInterface $previousItem */
            $previousItem = $this->itemsRegistry->getItemById($previousItemId);

            if ($previousItem) {
                $previousItem->addClild($item);
            }

            return;
        }

        $this->getTreeStructure()->addItem($item);
    }

    /**
     * @param string $lineContent
     *
     * @return int
     */
    protected function getLength($lineContent)
    {
        preg_match('/\\S/', $lineContent, $spacesFound, PREG_OFFSET_CAPTURE);
        $length = max(1, $spacesFound[0][1]);

        return $length;
    }
}
