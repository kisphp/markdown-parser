<?php

namespace Kisphp;

interface BlockInterface
{
    /**
     * @return string
     */
    public function parse();

    /**
     * @return string
     */
    public function getStartTag();

    /**
     * @return string
     */
    public function getEndTag();

    /**
     * @return int
     */
    public function getLineNumber();

    /**
     * @param int $lineNumber
     *
     * @return BlockInterface
     */
    public function setLineNumber($lineNumber);

    /**
     * @return mixed
     */
    public function getContent();

    /**
     * @param string $content
     *
     * @return BlockInterface
     */
    public function setContent($content);

    /**
     * @param int $lineNumber
     * @param DataObjectInterface $dataObject
     *
     * @return bool
     */
    public static function validateLineType($lineNumber, DataObjectInterface $dataObject);
}
