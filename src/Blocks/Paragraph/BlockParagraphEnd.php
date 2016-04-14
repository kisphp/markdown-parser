<?php

namespace Kisphp\Blocks\Paragraph;

use Kisphp\DataObjectInterface;

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
     * @param DataObjectInterface $dataObject
     *
     * @return $this
     */
    public function changeLineType(DataObjectInterface $dataObject)
    {
        return $this;
    }
}
