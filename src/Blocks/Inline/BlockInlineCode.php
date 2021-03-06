<?php

namespace Kisphp\Blocks\Inline;

use Kisphp\AbstractBlock;

class BlockInlineCode extends AbstractBlock
{
    /**
     * @return mixed|string
     */
    public function parse()
    {
        return preg_replace_callback('/(`+)[\s]*(.+?)[\s]*(?<!`)\1(?!`)/s', function ($found) {
            return $this->getStartTag() . htmlentities($found[2]) . $this->getEndTag();
        }, $this->content);
    }

    /**
     * @return string
     */
    public function getStartTag()
    {
        return '<code>';
    }

    /**
     * @return string
     */
    public function getEndTag()
    {
        return '</code>';
    }
}
