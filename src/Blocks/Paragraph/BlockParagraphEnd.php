<?php

namespace Kisphp\Blocks\Paragraph;

use Kisphp\DataObject;

class BlockParagraphEnd extends BlockParagraph
{
    /**
     * @return null
     */
    public function getStartTag()
    {
        return null;
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
