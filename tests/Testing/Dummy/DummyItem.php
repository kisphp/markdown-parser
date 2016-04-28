<?php

namespace Kisphp\Testing\Dummy;

use Kisphp\Blocks\Lists\Tree\ItemInterface;

class DummyItem implements ItemInterface
{
    public function setContent($content)
    {
        $this->content = $content;
    }

    public function setLevel($level)
    {
        // TODO: Implement setLevel() method.
    }

    public function getId()
    {
        // TODO: Implement getId() method.
    }

    public function setId($id)
    {
        // TODO: Implement setId() method.
    }

    public function getChildren()
    {
        // TODO: Implement getChildren() method.
    }

    public function addClild(ItemInterface $item)
    {
        // TODO: Implement addClild() method.
    }

    public function getListType()
    {
        // TODO: Implement getListType() method.
    }

    public function parse()
    {
        // TODO: Implement parse() method.
    }
}
