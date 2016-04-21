<?php

namespace Kisphp\Blocks\Paragraph;

use Kisphp\DataObjectInterface;

class BlockParagraphStart extends BlockParagraph
{
    /**
     * @return null
     */
    public function getEndTag()
    {
        return ' ';
    }
}
