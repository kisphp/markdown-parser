<?php

namespace Kisphp;

use Kisphp\Interfaces\BlockInterface;

class DataObject
{
    /**
     * @var array
     */
    protected $lines = [];

    /**
     * @param $markdownContent
     */
    public function __construct($markdownContent)
    {
        $markdownContent = $this->cleanupContent($markdownContent);

        $this->lines = explode("\n", $markdownContent);
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->lines);
    }

    /**
     * @param $markdownText
     *
     * @return string
     */
    protected function cleanupContent($markdownText)
    {
        $markdownText = str_replace(["\r\n", "\r"], "\n", $markdownText);

        return trim($markdownText, "\n");
    }

    /**
     * @return string
     */
    public function parseEachLine()
    {
        $html = '';
        /** @var BlockInterface $line */
        foreach ($this->lines as $line) {
            $html .= $line->parse();
        }

        return $html;
    }

    /**
     * @return mixed
     */
    public function getLines()
    {
        return $this->lines;
    }

    /**
     * @param int $lineNumber
     *
     * @return bool
     */
    public function hasLine($lineNumber)
    {
        return (bool) array_key_exists($lineNumber, $this->lines);
    }

    /**
     * @param int $lineNumber
     *
     * @return BlockInterface|null
     */
    public function getLine($lineNumber)
    {
        if (!$this->hasLine($lineNumber)) {
            return null;
        }

        return $this->lines[$lineNumber];
    }

    /**
     * @param int $key
     * @param BlockInterface $value
     *
     * @return $this
     */
    public function updateLine($key, BlockInterface $value)
    {
        $this->lines[$key] = $value;

        return $this;
    }
}
