<?php

namespace Kisphp\Blocks;

use Kisphp\AbstractBlockNoParse;
use Kisphp\DataObjectInterface;

class BlockContentBlocks extends AbstractBlockNoParse
{
    const BLOCK_CHARACTERS = ':::';

    /**
     * @var string
     */
    protected $contentBlockKey;

    /**
     * @param DataObjectInterface $dataObject
     *
     * @throws \Exception
     */
    public function changeLineType(DataObjectInterface $dataObject)
    {
        $max = $dataObject->count();
        $delimiterFound = 0;

        $blockContent = [];

        for ($i = $this->lineNumber; $i < $max; $i++) {
            $currentLine = $dataObject->getLine($i);
            $lineContent = $currentLine->getContent();

            $this->createSkipLine($dataObject, $i);

            if ($this->isBlockDelimiterLine($lineContent)) {
                $this->setContentBlockKeyByContent($lineContent);
                $delimiterFound++;
                continue;
            }

            if ($delimiterFound > 1) {
                break;
            }

            $blockContent[] = $lineContent;
        }

        $content = $this->getSubBlockParsedContent($blockContent);

        $dataObject->saveAvailableBlock($this->contentBlockKey, $content);
    }

    /**
     * @param int $lineNumber
     *
     * @return bool
     */
    public function validateLineType($lineNumber)
    {
        $lineContent = $this->factory
            ->getDataObject()
            ->getLine($lineNumber)
        ;

        return $this->isBlockDelimiterLine($lineContent);
    }

    /**
     * @param string $lineContent
     *
     * @throws \Exception
     *
     * @return $this
     */
    protected function setContentBlockKeyByContent($lineContent)
    {
        if ($this->contentBlockKey !== null) {
            return $this;
        }

        $key = str_replace(self::BLOCK_CHARACTERS, '', $lineContent);

        if (empty(trim($key))) {
            // @todo change this exception name
            throw new \Exception('No type provided in delimiter');
        }

        $this->contentBlockKey = trim($key);

        return $this;
    }

    /**
     * @param string $lineContent
     *
     * @return bool
     */
    protected function isBlockDelimiterLine($lineContent)
    {
        return (strpos($lineContent, self::BLOCK_CHARACTERS) === 0);
    }
}
