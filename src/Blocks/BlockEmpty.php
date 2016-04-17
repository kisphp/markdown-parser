<?php

namespace Kisphp\Blocks;

use Kisphp\AbstractBlockNoParse;

class BlockEmpty extends AbstractBlockNoParse
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
    public function getEndTag()
    {
        return "\n";
    }
}
