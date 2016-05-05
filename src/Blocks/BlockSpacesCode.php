<?php

namespace Kisphp\Blocks;

use Kisphp\AbstractBlockNoParse;
use Kisphp\BlockInterface;
use Kisphp\BlockTypes;
use Kisphp\DataObjectInterface;

class BlockSpacesCode extends BlockCode
{
    /**
     * @param DataObjectInterface $dataObject
     */
    public function changeLineType(DataObjectInterface $dataObject)
    {
        $blockContent = [];

        $max = $dataObject->count();
        for ($i = $this->lineNumber; $i < $max; $i++) {
            $lineObject = $dataObject->getLine($i);
            $lineContent = $lineObject->getContent();

            $blockContent[] = $this->encodeContent($lineContent);

            $this->createSkipLine($dataObject, $i);

            /** @var BlockInterface $nextLineObject */
            $nextLineObject = $dataObject->getLine($i + 1);
            if (!$this->lineIsObjectOf($nextLineObject, BlockTypes::BLOCK_SPACES_CODE)) {
                break;
            }
        }

        $lineContent = $this->getStartTag() . implode('', $blockContent) . $this->getEndTag();

        $currectLineObject = $this->factory->create(BlockTypes::BLOCK_UNCHANGE)
            ->setContent($lineContent)
            ->setLineNumber($this->lineNumber)
        ;
        $dataObject->updateLine($this->lineNumber, $currectLineObject);
    }

    /**
     * @param string $lineContent
     *
     * @return string
     */
    protected function encodeContent($lineContent)
    {
        $lineContent = preg_replace('/^([\s]{4})/', '', $lineContent);

        return parent::encodeContent($lineContent);
    }

    /**
     * @param int $lineNumber
     *
     * @return bool
     */
    public function validateLineType($lineNumber)
    {
        $dataObject = $this->factory->getDataObject();
        $lineContent = $dataObject->getLine($lineNumber);

        $trimmedLineContent = trim($lineContent);

        if (empty($trimmedLineContent)) {
            return false;
        }

        if (preg_match('/([\s]{4,}|[\t]{1,})/', $lineContent)) {
            return true;
        }

        return false;
    }
}
