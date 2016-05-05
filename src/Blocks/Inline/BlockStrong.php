<?php

namespace Kisphp\Blocks\Inline;

use Kisphp\AbstractBlock;

class BlockStrong extends AbstractBlock
{
    const DOUBLE_ASTERISKS = '**';
    const DOUBLE_UNDERSCORES = '__';

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
     * @param string $lineContent
     *
     * @return string
     */
    protected function convertStars($lineContent)
    {
        return preg_replace_callback('/([\*]{2}\S)(.*)(\S?[\*]{2})/U', function ($found) {

            return $this->getStartTag() . str_replace(static::DOUBLE_ASTERISKS, '', $found[0]) . $this->getEndTag();
        }, $lineContent);
    }

    /**
     * @param string $lineContent
     *
     * @return string
     */
    protected function convertUnderscores($lineContent)
    {
        return preg_replace_callback('/([\_]{2}\S)(.*)(\S?[\_]{2})/UU', function ($found) {

            return $this->getStartTag() . str_replace(static::DOUBLE_UNDERSCORES, '', $found[0]) . $this->getEndTag();
        }, $lineContent);
    }

    /**
     * @return string
     */
    public function getStartTag()
    {
        return '<strong>';
    }

    /**
     * @return string
     */
    public function getEndTag()
    {
        return '</strong>';
    }
}
