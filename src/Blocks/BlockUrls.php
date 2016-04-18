<?php

namespace Kisphp\Blocks;

use Kisphp\AbstractBlock;

class BlockUrls extends AbstractBlock
{
    /**
     * @return string
     */
    public function parse()
    {
        return preg_replace_callback('/\[(.*)\]\((.*)\)/', function ($found) {
            $text = (empty($found[1])) ? $found[2] : $found[1];

            $dictionary = [
                '{title}' => htmlentities($found[1]),
                '{url}' => urlencode($found[2]),
                '{text}' => $text,
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
        return '<a href="{url}" title="{title}">{text}';
    }

    /**
     * @return string
     */
    public function getEndTag()
    {
        return '</a>';
    }
}
