<?php

namespace Kisphp\Blocks;

use Kisphp\AbstractBlockNoParse;

/**
 * returns exactly the same content without any change
 */
class BlockUnchange extends AbstractBlockNoParse
{
    /**
     * @return string
     */
    public function parse()
    {
        return $this->getStartTag() . $this->content . $this->getEndTag();
    }
}
