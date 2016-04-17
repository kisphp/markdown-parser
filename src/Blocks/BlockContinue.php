<?php

namespace Kisphp\Blocks;

use Kisphp\AbstractBlockNoParse;

class BlockContinue extends AbstractBlockNoParse
{
    /**
     * @return string
     */
    public function parse()
    {
        return $this->content;
    }
}
