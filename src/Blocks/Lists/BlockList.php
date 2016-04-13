<?php

namespace Kisphp\Blocks\Lists;

use Kisphp\AbstractBlock;
use Kisphp\BlockFactory;
use Kisphp\BlockTypes;
use Kisphp\DataObject;

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
        // TODO: Implement getStartTag() method.
    }

    public function getEndTag()
    {
        // TODO: Implement getEndTag() method.
    }

    /**
     * @param DataObject $dataObject
     */
    public function changeLineType(DataObject $dataObject)
    {
        $max = $dataObject->count();
        $changeNextLine = true;

        $listTree = new ListTree();
        for ($i = $this->lineNumber; $i < $max; $i++) {
            $currentLineObject = $dataObject->getLine($i);

            $listTree->createItem($currentLineObject->getContent());

            /** @var AbstractBlock $nextLineObject */
            $nextLineObject = $dataObject->getLine($i + 1);
            /** @var AbstractBlock $nextSecondLineObject */
            $nextSecondLineObject = $dataObject->getLine($i + 2);

            if (!$this->lineIsObjectOf($nextLineObject, static::class)
//                && !$this->lineIsObjectOf($nextSecondLineObject, static::class)
            ) {
                $changeNextLine = false;
            }

            $changedContent = BlockFactory::create(BlockTypes::BLOCK_SKIP);
            $dataObject->updateLine($i, $changedContent);

            if ($changeNextLine === false) {
                break;
            }
        }

        $this->parseListTree($dataObject, $listTree);
    }

    /**
     * @param DataObject $dataObject
     * @param ListTree $listTree
     */
    protected function parseListTree(DataObject $dataObject, ListTree $listTree)
    {
        $newContent = BlockFactory::create(BlockTypes::BLOCK_UNCHANGE)
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
