<?php

namespace Kisphp\Blocks;

use Kisphp\AbstractBlock;

/**
 * returns exactly the same content without any change
 */
class BlockUnchange extends AbstractBlock
{
    /**
     * @return string
     */
    public function parse()
    {
        return $this->getStartTag() . $this->content . $this->getEndTag();
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
