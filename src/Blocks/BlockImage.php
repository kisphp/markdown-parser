<?php

namespace Kisphp\Blocks;

use Kisphp\AbstractBlock;

class BlockImage extends AbstractBlock
{
    /**
     * @return string
     */
    public function parse()
    {
        return preg_replace_callback('/\!\[(.*)\]\((.*)\)/', function ($found) {
            $dictionary = [
                '{alt}' => htmlentities($found[1]),
                '{src}' => urlencode($found[2]),
            ];

            $content = $this->getStartTag() . $this->getEndTag();

            return str_replace(
                array_keys($dictionary),
                $dictionary,
                $content
            );
        }, $this->content);
    }

    /**
     * @return string
     */
    public function getStartTag()
    {
        return '<img src="{src}" alt="{alt}"';
    }

    /**
     * @return string
     */
    public function getEndTag()
    {
        return ' />';
    }
}
