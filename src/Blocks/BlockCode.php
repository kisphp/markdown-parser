<?php

namespace Kisphp\Blocks;

use Kisphp\AbstractBlock;
use Kisphp\BlockFactory;
use Kisphp\BlockTypes;
use Kisphp\DataObject;

class BlockCode extends AbstractBlock
{
    /**
     * @return string
     */
    public function parse()
    {
        return '';
    }

    /**
     * @param string $lineContent
     *
     * @return string
     */
    protected function getCodeType($lineContent)
    {
        $lineContent = str_replace('```', '', $lineContent);

        if (!empty($lineContent)) {
            return $lineContent;
        }

        return '';
    }

    /**
     * @param string|null $class
     *
     * @return string
     */
    public function getStartTag($class = null)
    {
        $tagClass = '';
        if ($class !== null && is_string($class)) {
            $tagClass = ' class="' . $class . '"';
        }

        return '<pre><code' . $tagClass . '>';
    }

    /**
     * @return string
     */
    public function getEndTag()
    {
        return '</code></pre>';
    }

    /**
     * @param DataObject $dataObject
     */
    public function changeLineType(DataObject $dataObject)
    {
        $max = $dataObject->count();
        $isStart = false;

        for ($i = $this->lineNumber; $i < $max; $i++) {
            $lineObject = $dataObject->getLine($i);
            $lineContent = $lineObject->getContent();
            if (strpos($lineContent, '```') === 0 && $isStart === false) {
                $isStart = true;

                $newLineContent = $this->getStartTag($this->getCodeType($lineContent));

                $newObject = BlockFactory::create(BlockTypes::BLOCK_UNCHANGE)
                    ->setContent($newLineContent)
                    ->setLineNumber($i)
                ;
                $dataObject->updateLine($i, $newObject);

                continue;
            }

            if ($i >= ($max - 1) || (strpos($lineContent, '```') === 0 && $isStart === true)) {
                $newObject = BlockFactory::create(BlockTypes::BLOCK_UNCHANGE)
                    ->setContent($this->getEndTag() . "\n")
                    ->setLineNumber($i)
                ;
                $dataObject->updateLine($i, $newObject);

                break;
            }

            $newObject = BlockFactory::create(BlockTypes::BLOCK_UNCHANGE)
                ->setContent($this->encodeContent($lineContent))
                ->setLineNumber($i)
            ;

            $dataObject->updateLine($i, $newObject);
        }
    }

    /**
     * @param string $lineContent
     *
     * @return string
     */
    protected function encodeContent($lineContent)
    {
        return htmlentities($lineContent) . "\n";
    }
}
