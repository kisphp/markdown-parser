<?php

namespace Kisphp\Blocks\Headers;

class BlockHeaderTwo extends AbstractBlockSpecialHeader
{
    /**
     * @return string
     */
    public function parse()
    {
        return $this->getStartTag() . $this->content . $this->getEndTag();
    }

    /**
     * @return string
     */
    public function getStartTag()
    {
        return '<h2>';
    }

    /**
     * @return string
     */
    public function getEndTag()
    {
        return '</h2>';
    }
}
