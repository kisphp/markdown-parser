<?php

namespace Kisphp\Blocks\Headers;

use Kisphp\AbstractBlock;
use Kisphp\BlockInterface;
use Kisphp\BlockTypes;
use Kisphp\DataObjectInterface;

abstract class AbstractBlockSpecialHeader extends AbstractBlock
{
    /**
     * @param DataObjectInterface $dataObject
     *
     * @return BlockInterface
     */
    public function changeLineType(DataObjectInterface $dataObject)
    {
        $previousLine = $this->lineNumber - 1;
        $previousLineObject = $dataObject->getLine($previousLine);

        $previousLineNewObject = new static($this->factory);
        $previousLineNewObject->setContent($previousLineObject->getContent());
        $previousLineNewObject->setLineNumber($previousLineObject->getLineNumber());

        $dataObject->updateLine($previousLine, $previousLineNewObject);

        $changedBlockObject = $this->changeObjectType(BlockTypes::BLOCK_EMPTY);
        $dataObject->updateLine(
            $this->getLineNumber(),
            $changedBlockObject
        );

        return $changedBlockObject;
    }
}
