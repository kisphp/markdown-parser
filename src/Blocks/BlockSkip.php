<?php

namespace Kisphp\Blocks;

use Kisphp\AbstractBlock;

/**
 * this block removes unnecessary empty lines from code
 */
class BlockSkip extends AbstractBlock
{
    /**
     * @return string
     */
    public function parse()
    {
        return $this->getStartTag() . $this->getEndTag();
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
