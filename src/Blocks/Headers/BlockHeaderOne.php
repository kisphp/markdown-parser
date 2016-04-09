<?php

namespace Kisphp\Blocks\Headers;

class BlockHeaderOne extends AbstractBlockSpecialHeader
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
        return '<h1>';
    }

    /**
     * @return string
     */
    public function getEndTag()
    {
        return '</h1>';
    }
}
