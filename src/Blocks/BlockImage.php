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
                '{1}' => htmlentities($found[1]),
                '{2}' => urlencode($found[2]),
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
        return '<img src="{2}" alt="{1}"';
    }

    /**
     * @return string
     */
    public function getEndTag()
    {
        return ' />';
    }
}
