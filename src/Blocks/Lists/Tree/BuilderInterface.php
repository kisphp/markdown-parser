<?php

namespace Kisphp\Blocks\Lists\Tree;

use Kisphp\BlockInterface;

interface BuilderInterface
{
    /**
     * @param ItemInterface $item
     *
     * @return string
     */
    public function createListStartTag(ItemInterface $item);

    /**
     * @param ItemInterface $item
     *
     * @return string
     */
    public function createListEndTag(ItemInterface $item);

    /**
     * @return string
     */
    public function createListItemStartTag();

    /**
     * @return string
     */
    public function createListItemEndTag();

    /**
     * @param BlockInterface $block
     */
    public function addItem(BlockInterface $block);

    /**
     * @return TreeStructureInterface
     */
    public function getTreeStructure();
}
