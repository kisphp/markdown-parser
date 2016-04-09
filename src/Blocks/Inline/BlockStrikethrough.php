<?php

namespace Kisphp\Blocks\Inline;

use Kisphp\AbstractBlock;

class BlockStrikethrough extends AbstractBlock
{
    /**
     * @return string
     */
    public function parse()
    {
        return preg_replace_callback('/([\~]{2})(.*)([\~]{2})/', function ($found) {

            return $this->getStartTag() . $found[2] . $this->getEndTag();

        }, $this->content);
    }

    public function getStartTag()
    {
        return '<del>';
    }

    public function getEndTag()
    {
        return '</del>';
    }
}
