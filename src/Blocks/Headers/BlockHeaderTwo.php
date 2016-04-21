<?php

namespace Kisphp\Blocks\Headers;

use Kisphp\BlockTypes;

class BlockHeaderTwo extends AbstractBlockSpecialHeader
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
        return '<h2>';
    }

    /**
     * @return string
     */
    public function getEndTag()
    {
        return '</h2>';
    }

    /**
     * @param int $lineNumber
     *
     * @return bool
     */
    public function validateLineType($lineNumber)
    {
        $dataObject = $this->factory->getDataObject();
        $currentLineObject = $dataObject->getLine($lineNumber);
        if ($lineNumber < 1 || strpos($currentLineObject, '|') !== false) {
            return false;
        }

        $previousLineObject = $dataObject->getLine($lineNumber - 1);
        $paragraphNamespace = $this->factory->getClassNamespace(BlockTypes::BLOCK_PARAGRAPH);

        if (!$this->lineIsObjectOf($previousLineObject, $paragraphNamespace)) {
            return false;
        }

        return (bool) preg_match('/([\-]{3,})/', $currentLineObject);
    }
}
