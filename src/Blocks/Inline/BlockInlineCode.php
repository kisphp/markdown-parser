<?php

namespace Kisphp\Blocks\Inline;

use Kisphp\AbstractBlock;

class BlockInlineCode extends AbstractBlock
{
    /**
     * @return mixed|string
     */
    public function parse()
    {
        if (strpos($this->content, ' ') === 0) {
            $preformatedText = true;

            return $this->getStartTag($preformatedText) . trim($this->content) . $this->getEndTag($preformatedText);
        }

        return preg_replace_callback('/(`+)[\s]*(.+?)[\s]*(?<!`)\1(?!`)/s', function ($found) {

            return $this->getStartTag() . $found[2] . $this->getEndTag();

        }, $this->content);
    }

    /**
     * @return string
     */
    public function getStartTag($preformatedText = false)
    {
        return (($preformatedText === true) ? '<pre>' : '') . '<code>';
    }

    /**
     * @return string
     */
    public function getEndTag($preformatedText = false)
    {
        return '</code>' . (($preformatedText === true) ? '</pre>' : '');
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
        if (preg_match('/([\s]{4,}|[\t]{1,})/', $lineContent)) {
            return true;
        }

        return false;
    }
}
