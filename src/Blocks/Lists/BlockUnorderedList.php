<?php

namespace Kisphp\Blocks\Lists;

class BlockUnorderedList extends BlockList
{
    /**
     * @return string
     */
    public function getStartTag()
    {
        return '<ul>';
    }

    /**
     * @return string
     */
    public function getEndTag()
    {
        return '</ul>';
    }
}
