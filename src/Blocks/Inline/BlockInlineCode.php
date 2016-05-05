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

            return $this->getStartTag() . htmlentities($found[2]) . $this->getEndTag();

        }, $this->content);
    }

    /**
     * @return string
     */
    public function getStartTag($preformatedText = false)
    {
        return (($preformatedText === true) ? $this->getStartPreTag() : '') . $this->getStartCodeTag();
    }

    /**
     * @return string
     */
    public function getEndTag($preformatedText = false)
    {
        return $this->getEndCodeTag() . (($preformatedText === true) ? $this->getEndPreTag() : '');
    }

    /**
     * @return string
     */
    protected function getStartPreTag()
    {
        return '<pre>';
    }

    /**
     * @return string
     */
    protected function getStartCodeTag()
    {
        return '<code>';
    }

    /**
     * @return string
     */
    protected function getEndPreTag()
    {
        return '</pre>';
    }

    /**
     * @return string
     */
    protected function getEndCodeTag()
    {
        return '</code>';
    }
}
