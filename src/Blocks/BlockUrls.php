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
        return preg_replace_callback('/\[(.*)\]\((.*)\)/U', function ($found) {
            $dictionary = $this->getDictionary($found);

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

    /**
     * @param array $foundMatches
     *
     * @return array
     */
    protected function getDictionary(array $foundMatches)
    {
        $text = (empty($foundMatches[1])) ? $foundMatches[2] : $foundMatches[1];

        $dictionary = [
            '{title}' => htmlentities($foundMatches[1]),
            '{url}' => $foundMatches[2],
            '{text}' => $text,
        ];

        if (strpos($foundMatches[1], '<') === 0) {
            $dictionary['{title}'] = '';
        }

        return $dictionary;
    }
}
