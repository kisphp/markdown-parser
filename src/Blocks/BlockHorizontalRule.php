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
     * @return null
     */
    public function getStartTag()
    {
        return null;
    }

    /**
     * @return string
     */
    public function getEndTag()
    {
        return '<hr />' . "\n";
    }
}
