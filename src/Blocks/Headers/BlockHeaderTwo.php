<?php

namespace Kisphp\Blocks\Headers;

use Kisphp\Blocks\BlockEmpty;
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

    /**
     * @param DataObjectInterface $dataObject
     *
     * @return BlockInterface
     */
    public function changeLineType2(DataObjectInterface $dataObject)
    {
        $previousLine = $this->lineNumber - 1;
        if (!$dataObject->hasLine($previousLine)) {
            return $this;
        }

        $previousLineObject = $dataObject->getLine($previousLine);
        if (is_a($previousLineObject, BlockEmpty::class)) {
            $guess = $this->factory->createRowTypeGuesser();
            if ($guess->isBlockHorizontalRule($this->content)) {
                $newObject = $this->factory->create(BlockTypes::BLOCK_HORIZONTAL_RULE)
                    ->setContent($this->content)
                    ->setLineNumber($this->lineNumber)
                ;

                $dataObject->updateLine($this->lineNumber, $newObject);

                return $newObject;
            }
        }

        return parent::changeLineType($dataObject);
    }
}
