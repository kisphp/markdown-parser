<?php

namespace Kisphp\Blocks;

use Kisphp\AbstractBlockNoParse;
use Kisphp\BlockTypes;
use Kisphp\DataObjectInterface;

class BlockCode extends AbstractBlockNoParse
{
    const BACKTICK_CODE = '96';
    const BLOCK_MARKUP = '```';

    /**
     * @param string $lineContent
     *
     * @return string
     */
    protected function getCodeType($lineContent)
    {
        $lineContent = str_replace(static::BLOCK_MARKUP, '', $lineContent);

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

        return '<pre><code' . $tagClass . '>';
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

        $blockContent = [];
        $codeClass = '';

        for ($i = $this->lineNumber; $i < $max; $i++) {
            $lineObject = $dataObject->getLine($i);
            $lineContent = $lineObject->getContent();
            if (strpos($lineContent, static::BLOCK_MARKUP) === 0 && $isStart === false) {
                $isStart = true;

                $codeClass = $this->getCodeType($lineContent);

                $newObject = $this->factory->create(BlockTypes::BLOCK_SKIP)
                    ->setContent('')
                    ->setLineNumber($i)
                ;
                $dataObject->updateLine($i, $newObject);

                continue;
            }

            if (strpos($lineContent, static::BLOCK_MARKUP) === false) {
                $blockContent[] = $this->encodeContent($lineContent);
            }

            if ($i >= ($max - 1) || (strpos($lineContent, static::BLOCK_MARKUP) === 0 && $isStart === true)) {
                $newObject = $this->factory->create(BlockTypes::BLOCK_SKIP)
                    ->setContent('')
                    ->setLineNumber($i)
                ;
                $dataObject->updateLine($i, $newObject);

                break;
            }

            $newObject = $this->factory->create(BlockTypes::BLOCK_SKIP)
                ->setContent('')
                ->setLineNumber($i)
            ;

            $dataObject->updateLine($i, $newObject);
        }

        $lineContent = $this->getStartTag($codeClass) . implode('', $blockContent) . $this->getEndTag();

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
        if (!isset($counter[static::BACKTICK_CODE]) || $counter[static::BACKTICK_CODE] !== 3) {
            return false;
        }

        return (bool) preg_match('/^([\`]{3})/', $lineContent);
    }
}
