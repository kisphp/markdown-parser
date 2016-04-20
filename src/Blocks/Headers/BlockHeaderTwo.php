<?php

namespace Kisphp\Blocks\Headers;

use Kisphp\BlockTypes;
use Kisphp\DataObjectInterface;

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

    public static function validateLineType($lineNumber, DataObjectInterface $dataObject)
    {
        if ($lineNumber < 1) {
            return false;
        }

        $previousLineObject = $dataObject->getLine($lineNumber - 1);
//        if (!$this->isLineTypeOf($lineNumber - 1, BlockTypes::BLOCK_PARAGRAPH)) {
        if ($previousLineObject instanceof BlockTypes::BLOCK_PARAGRAPHS) {
            return false;
        }

        return (bool) preg_match('/([\-]{3,})/', $dataObject->getLine($lineNumber));
    }


}
