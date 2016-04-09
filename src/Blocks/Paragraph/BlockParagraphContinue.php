<?php

namespace Kisphp\Blocks\Paragraph;

use Kisphp\DataObject;

class BlockParagraphContinue extends BlockParagraph
{
    /**
     * @return null
     */
    public function getStartTag()
    {
        return null;
    }

    /**
     * @return null
     */
    public function getEndTag()
    {
        return ' ';
    }

    /**
     * @param DataObject $dataObject
     *
     * @return $this
     */
    public function changeLineType(DataObject $dataObject)
    {
        return $this;
    }
}
