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
                '{url}' => $found[2],
                '{text}' => $text,
            ];

            $isTargetBlank = $this->isTargetBlank($found[0]);
            $content = $this->getStartTag($isTargetBlank) . $this->getEndTag();

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
    public function getStartTag($targetBlank = false)
    {
        $htmlUrl = '<a href="{url}" title="{title}"';
        if ($targetBlank === true) {
            $htmlUrl .= ' target="_blank"';
        }
        $htmlUrl .= '>{text}';

        return $htmlUrl;
    }

    /**
     * @return string
     */
    public function getEndTag()
    {
        return '</a>';
    }

    /**
     * @param string $url
     *
     * @return bool
     */
    protected function isTargetBlank($url)
    {
        return (bool) strpos($url, 'http');
    }
}
