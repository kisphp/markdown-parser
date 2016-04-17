<?php

namespace Kisphp\Blocks\Lists;

use Kisphp\AbstractBlock;
use Kisphp\Blocks\Lists\Tree\Builder;
use Kisphp\BlockTypes;
use Kisphp\DataObjectInterface;

class BlockList extends AbstractBlock
{

    /**
     * @return string
     */
    public function parse()
    {
        $html = $this->getStartTag() . $this->clearListMarkup($this->content) . $this->getEndTag();

        return $this->parseInlineMarkup($html);
    }

    public function getStartTag()
    {
        return null;
    }

    public function getEndTag()
    {
        return null;
    }

    /**
     * @param DataObjectInterface $dataObject
     */
    public function changeLineType(DataObjectInterface $dataObject)
    {
        $max = $dataObject->count();
        $changeNextLine = true;

        $builder = new Builder();
        for ($i = $this->lineNumber; $i < $max; $i++) {
            $currentLineObject = $dataObject->getLine($i);

            $builder->addItem($currentLineObject);

            /** @var AbstractBlock $nextLineObject */
            $nextLineObject = $dataObject->getLine($i + 1);

            if (!$this->lineIsObjectOf($nextLineObject, static::class)) {
                $changeNextLine = false;
            }

            $changedContent = $this->factory->create(BlockTypes::BLOCK_SKIP);
            $dataObject->updateLine($i, $changedContent);

            if ($changeNextLine === false) {
                break;
            }
        }

        $listContent = $this->factory->create(BlockTypes::BLOCK_UNCHANGE)
            ->setContent($builder->getTreeStructure()->parse())
            ->setLineNumber($this->lineNumber)
        ;

        $dataObject->updateLine($this->lineNumber, $listContent);
    }

    /**
     * @param DataObjectInterface $dataObject
     * @param ListTree $listTree
     *
     * @deprecated not used any more
     */
    protected function parseListTree(DataObjectInterface $dataObject, ListTree $listTree)
    {
        $newContent = $this->factory->create(BlockTypes::BLOCK_UNCHANGE)
            ->setContent($listTree->parse())
        ;

        $dataObject->updateLine($this->getLineNumber(), $newContent);
    }

    /**
     * @param string $lineContent
     *
     * @return string
     *
     * @deprecated not needed any more
     */
    protected function createLineContent($lineContent)
    {
        $content = $this->clearListMarkup($lineContent);

        return $content;
    }
}
