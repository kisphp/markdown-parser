<?php

namespace Kisphp\Blocks;

use Kisphp\AbstractBlock;

class BlockTable extends AbstractBlock
{
    public function parse()
    {
        return $this->content;
    }

    public function getStartTag()
    {
        // TODO: Implement getStartTag() method.
    }

    public function getEndTag()
    {
        // TODO: Implement getEndTag() method.
    }
}