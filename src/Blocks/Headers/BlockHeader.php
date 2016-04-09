<?php

namespace Kisphp\Blocks\Headers;

use Kisphp\AbstractBlock;

class BlockHeader extends AbstractBlock
{
    /**
     * @return string
     */
    public function parse()
    {
        $text = '';
        for ($i=1; $i>0; $i--) {
            $text = preg_replace_callback('/^([\#]{' . $i . ',})\s(.*)/', function ($found) {
                $number = strlen($found[1]);

                return $this->getStartTag($number) . trim($found[2]) . $this->getEndTag($number);
            }, $this->content);
        }

        return $text;
    }

    /**
     * @param int $number
     *
     * @return string
     */
    public function getStartTag($number = 1)
    {
        return '<h' . (int) $number . '>';
    }

    /**
     * @param int $number
     *
     * @return string
     */
    public function getEndTag($number = 1)
    {
        return '</h' . (int) $number . '>' . "\n";
    }
}
