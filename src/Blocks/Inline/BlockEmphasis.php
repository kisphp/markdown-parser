<?php

namespace Kisphp\Blocks\Inline;

use Kisphp\AbstractBlock;

class BlockEmphasis extends AbstractBlock
{
    /**
     * @return string
     */
    public function parse()
    {
        $this->content = $this->convertStars($this->content);
        $this->content = $this->convertUnderscores($this->content);

        return $this->content;
    }

    /**
     * @return string
     */
    public function getStartTag()
    {
        return '<em>';
    }

    /**
     * @return string
     */
    public function getEndTag()
    {
        return '</em>';
    }

    /**
     * @param string $lineContent
     *
     * @return string
     */
    protected function convertStars($lineContent)
    {
        return preg_replace_callback('/([\*]{1})(\S+)(.*)(\S+)([\*]{1})/', function ($found) {
            if ($found[0] === '**') {
                return $found[0];
            }

            return $this->getStartTag() . str_replace('*', '', $found[0]) . $this->getEndTag();
        }, $lineContent);
    }

    /**
     * @param string $lineContent
     *
     * @return string
     */
    protected function convertUnderscores($lineContent)
    {
        return preg_replace_callback('/([\_]{1})(\S+)(.*)(\S+)([\_]{1})/', function ($found) {
            if ($found[0] === '__') {
                return $found[0];
            }

            return $this->getStartTag() . str_replace('_', '', $found[0]) . $this->getEndTag();
        }, $lineContent);
    }
}
