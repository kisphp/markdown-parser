<?php

namespace Kisphp\Blocks\Inline;

use Kisphp\AbstractBlock;

class BlockNewLines extends AbstractBlock
{
    public function parse()
    {
        if (empty(trim($this->content))) {
            return $this->content;
        }

//        $this->content = preg_replace('/([\s]{2,})/', '<br>', ltrim($this->content));

        return $this->content;
    }

    public function getStartTag()
    {
        return null;
    }

    public function getEndTag()
    {
        return '<br>';
    }
}
