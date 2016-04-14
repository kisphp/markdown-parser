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

    /**
     * @return RowTypeGuesserInterface
     */
    public function getRowTypeGuesser();

    /**
     * @return DataObjectInterface
     */
    public function getDataObject();
}
