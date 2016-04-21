<?php

namespace Kisphp\Blocks;

use Kisphp\AbstractBlockNoParse;
use Kisphp\BlockTypes;
use Kisphp\DataObjectInterface;

class BlockCode extends AbstractBlockNoParse
{
    const BACKTICK_CODE = '96';

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
        if (!empty($class) && is_string($class)) {
            $tagClass = ' class="language-' . $class . '"';
        }

        return '<pre' . $tagClass . '><code>';
    }

    /**
     * @return string
     */
    public function getEndTag()
    {
        return '</code></pre>' . "\n";
    }

    /**
     * @param DataObjectInterface $dataObject
     */
    public function changeLineType(DataObjectInterface $dataObject)
    {
        $max = $dataObject->count();
        $isStart = false;

        for ($i = $this->lineNumber; $i < $max; $i++) {
            $lineObject = $dataObject->getLine($i);
            $lineContent = $lineObject->getContent();
            if (strpos($lineContent, '```') === 0 && $isStart === false) {
                $isStart = true;

                $newLineContent = $this->getStartTag($this->getCodeType($lineContent));

                $newObject = $this->factory->create(BlockTypes::BLOCK_UNCHANGE)
                    ->setContent($newLineContent)
                    ->setLineNumber($i)
                ;
                $dataObject->updateLine($i, $newObject);

                continue;
            }

            if ($i >= ($max - 1) || (strpos($lineContent, '```') === 0 && $isStart === true)) {
                $newObject = $this->factory->create(BlockTypes::BLOCK_UNCHANGE)
                    ->setContent($this->getEndTag())
                    ->setLineNumber($i)
                ;
                $dataObject->updateLine($i, $newObject);

                break;
            }

            $newObject = $this->factory->create(BlockTypes::BLOCK_UNCHANGE)
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

    /**
     * @param int $lineNumber
     *
     * @return bool
     */
    public function validateLineType($lineNumber)
    {
        $dataObject = $this->factory->getDataObject();
        $lineContent = $dataObject->getLine($lineNumber);
        $counter = count_chars($lineContent, 1);
        if (!isset($counter[self::BACKTICK_CODE]) || $counter[self::BACKTICK_CODE] !== 3) {
            return false;
        }

        return (bool) preg_match('/^([\`]{3})/', $lineContent);
    }
}
