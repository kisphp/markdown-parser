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
            return $this->parseLine($found);
        }, $this->content);
    }

    /**
     * @return string
     *
     * @param mixed $targetBlank
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
     * @param array $found
     *
     * @return string
     */
    protected function parseLine(array $found)
    {
        $dictionary = $this->getDictionary($found);

        $char = strpos($found[1], '[');
        if ($char !== false) {
            $text = substr($found[0], 0, $char);
            $this->content = substr($found[0], $char);

            return $text . $this->parse();
        }

        $isTargetBlank = $this->isTargetBlank($found[0]);
        $content = $this->getStartTag($isTargetBlank) . $this->getEndTag();

        return str_replace(
            array_keys($dictionary),
            $dictionary,
            $content
        );
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
