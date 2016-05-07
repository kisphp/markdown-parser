<?php

namespace Kisphp\Blocks\Lists;

use Kisphp\AbstractBlockNoParse;
use Kisphp\BlockInterface;
use Kisphp\Blocks\Lists\Tree\Builder;
use Kisphp\BlockTypes;
use Kisphp\DataObjectInterface;

class BlockList extends AbstractBlockNoParse
{
    /**
     * @param DataObjectInterface $dataObject
     */
    public function changeLineType(DataObjectInterface $dataObject)
    {
        $max = $dataObject->count();

        $builder = $this->createBuilder();
        for ($i = $this->lineNumber; $i < $max; $i++) {
            $currentLineObject = $dataObject->getLine($i);

            $builder->addItem($currentLineObject);

            $changedContent = $this->factory->create(BlockTypes::BLOCK_SKIP);
            $dataObject->updateLine($i, $changedContent);

            /** @var BlockInterface $nextLineObject */
            $nextLineObject = $dataObject->getLine($i + 1);
            if (!$this->lineIsObjectOf($nextLineObject, static::class)) {
                break;
            }
        }

        $listHtmlContent = $this->parseInlineMarkup($builder->getTreeStructure()->parse());
        $listContent = $this->factory->create(BlockTypes::BLOCK_UNCHANGE)
            ->setContent($listHtmlContent)
            ->setLineNumber($this->lineNumber)
        ;

        $dataObject->updateLine($this->lineNumber, $listContent);
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
        $lineContent = trim($lineContent);

        return (
            $this->isBlockOrderedListByContent($lineContent)
            || $this->isBlockUnorderedListByContent($lineContent)
        );
    }

    /**
     * @return Builder
     */
    protected function createBuilder()
    {
        return new Builder();
    }

    /**
     * @param string $lineContent
     *
     * @return bool
     */
    protected function isBlockOrderedListByContent($lineContent)
    {
        return (bool) preg_match('/(^\*\s|^\-\s|^\+\s)/', $lineContent);
    }

    /**
     * @param string $lineContent
     *
     * @return bool
     */
    protected function isBlockUnorderedListByContent($lineContent)
    {
        return (bool) preg_match('/(^[0-9]\.\s)/', $lineContent);
    }
}
