<?php

namespace Kisphp;

interface MarkdownInterface
{
    /**
     * @param string $text
     *
     * @return string
     */
    public function parse($text);
}
