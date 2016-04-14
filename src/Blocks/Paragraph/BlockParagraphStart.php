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

    /**
     * @param DataObjectInterface $dataObject
     *
     * @return $this
     */
    public function changeLineType(DataObjectInterface $dataObject)
    {
        return $this;
    }
}
