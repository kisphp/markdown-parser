<?php

namespace Kisphp\Blocks\Headers;

use Kisphp\BlockTypes;

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

    public function validateLineType($lineNumber)
    {
        if ($lineNumber < 1) {
            return false;
        }
        $dataObject = $this->factory->getDataObject();
        $previousLineType = $dataObject->getLine($lineNumber - 1);
        if (!$this->lineIsObjectOf($previousLineType, BlockTypes::BLOCK_PARAGRAPH)) {
            return false;
        }

        return (bool) preg_match('/([\=]{3,})/', $dataObject->getLine($lineNumber));
    }
}
