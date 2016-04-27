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
        return preg_replace_callback('/\!\[(.*)\]\((.*)\)/U', function ($found) {

            $dictionary = $this->getDictionary($found);

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

    /**
     * @param array $foundMatches
     *
     * @return array
     */
    protected function getDictionary(array $foundMatches)
    {
        $dictionary = [
            '{alt}' => htmlentities($foundMatches[1]),
            '{src}' => urlencode($foundMatches[2]),
        ];

        return $dictionary;
    }
}
