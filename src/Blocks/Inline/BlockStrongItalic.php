<?php

namespace Kisphp\Blocks\Inline;

use Kisphp\AbstractBlock;

class BlockStrongItalic extends AbstractBlock
{
    const TRIPLE_ASTERISKS = '***';
    const TRIPLE_UNDERSCORES = '___';

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
        return '<strong><em>';
    }

    /**
     * @return string
     */
    public function getEndTag()
    {
        return '</em></strong>';
    }

    /**
     * @param string $lineContent
     *
     * @return string
     */
    protected function convertStars($lineContent)
    {
        return preg_replace_callback('/([\*]{3})(\S+)(.*|)(\S+)([\*]{3})/U', function ($found) {

            return $this->getStartTag() . str_replace(static::TRIPLE_ASTERISKS, '', $found[0]) . $this->getEndTag();
        }, $lineContent);
    }

    /**
     * @param string $lineContent
     *
     * @return string
     */
    protected function convertUnderscores($lineContent)
    {
        return preg_replace_callback('/([\_]{3})(\S+)(.*)(\S+)([\_]{3})/U', function ($found) {

            return $this->getStartTag() . str_replace(static::TRIPLE_UNDERSCORES, '', $found[0]) . $this->getEndTag();
        }, $lineContent);
    }
}
