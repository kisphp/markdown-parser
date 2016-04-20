<?php

namespace Kisphp\Blocks\Lists\Tree;

interface TreeStructureInterface
{
    /**
     * @param ItemInterface $item
     */
    public function addItem(ItemInterface $item);

    /**
     * @return string
     */
    public function parse();
}
