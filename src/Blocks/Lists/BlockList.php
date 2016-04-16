<?php

namespace Kisphp\Blocks\Lists;

use Kisphp\AbstractBlock;
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

        $listTree = new ListTree();
        for ($i = $this->lineNumber; $i < $max; $i++) {
            $currentLineObject = $dataObject->getLine($i);

            $listTree->createItem($currentLineObject);

            /** @var AbstractBlock $nextLineObject */
            $nextLineObject = $dataObject->getLine($i + 1);
            /** @var AbstractBlock $nextSecondLineObject */
//            $nextSecondLineObject = $dataObject->getLine($i + 2);

            if (!$this->lineIsObjectOf($nextLineObject, static::class)) {
                $changeNextLine = false;
            }

            $changedContent = $this->factory->create(BlockTypes::BLOCK_SKIP);
            $dataObject->updateLine($i, $changedContent);

            if ($changeNextLine === false) {
                break;
            }
        }

        $this->parseListTree($dataObject, $listTree);
    }

    /**
     * @param DataObjectInterface $dataObject
     * @param ListTree $listTree
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
