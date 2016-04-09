<?php

namespace Kisphp\Blocks;

use Kisphp\AbstractBlock;

class BlockEmpty extends AbstractBlock
{
    /**
     * @return string
     */
    public function parse()
    {
        return "\n";
    }

    /**
     * @return null
     */
    public function getStartTag()
    {
        return null;
    }

    /**
     * @return null
     */
    public function getEndTag()
    {
        return null;
    }
}
