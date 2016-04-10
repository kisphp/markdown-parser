<?php

namespace Kisphp\Blocks\Lists;

class BlockOrderedList extends BlockList
{
    /**
     * @return string
     */
    public function getStartTag()
    {
        return '<ol>';
    }

    /**
     * @return string
     */
    public function getEndTag()
    {
        return '</ol>';
    }
}
