<?php

namespace Kisphp\Blocks\Inline;

use Kisphp\AbstractBlock;

class BlockStrongItalic extends AbstractBlock
{
    public function parse()
    {
        return $this->getStartTag() . $this->cleanupMarkup($this->content) . $this->getEndTag();
    }

    public function getStartTag()
    {
        return '<strong><em>';
    }

    public function getEndTag()
    {
        return '</em></strong>';
    }

    protected function cleanupMarkup($lineContent)
    {
        return str_replace(['***', '___'], '', $lineContent);
    }
}