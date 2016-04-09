<?php

namespace Kisphp\Blocks;

use Kisphp\AbstractBlock;

class BlockHorizontalRule extends AbstractBlock
{
    /**
     * @return string
     */
    public function parse()
    {
        return $this->getStartTag() . $this->getEndTag();
    }

    /**
     * @return string
     */
    public function getStartTag()
    {
        return '<hr />';
    }

    /**
     * @return null
     */
    public function getEndTag()
    {
        return null;
    }
}
