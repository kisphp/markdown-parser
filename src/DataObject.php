<?php

namespace Kisphp;

use Kisphp\Exceptions\DataObjectBlockAlreadyExists;

class DataObject implements DataObjectInterface
{
    /**
     * @var array
     */
    protected $lines = [];

    /**
     * @var array
     */
    protected $availableBlocks = [];

    /**
     * @param string $markdownContent
     */
    public function __construct($markdownContent)
    {
        $markdownContent = $this->cleanupContent($markdownContent);

        $this->lines = explode("\n", $markdownContent);
    }

    /**
     * @param string $blockUniqueKey
     * @param string $renderedBlockContent
     *
     * @throws DataObjectBlockAlreadyExists
     *
     * @return $this
     */
    public function saveAvailableBlock($blockUniqueKey, $renderedBlockContent)
    {
        if (isset($this->availableBlocks[$blockUniqueKey]) && !empty($this->availableBlocks[$blockUniqueKey])) {
            throw new DataObjectBlockAlreadyExists();
        }

        $this->availableBlocks[$blockUniqueKey] = $renderedBlockContent;

        return $this;
    }

    /**
     * @param string $blockKey
     *
     * @return string
     */
    public function getBlockByKey($blockKey)
    {
        if (!isset($this->availableBlocks[$blockKey])) {
            return '';
        }

        return $this->availableBlocks[$blockKey];
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
        $html = [];
        /** @var BlockInterface $line */
        foreach ($this->lines as $line) {
            $html[] = $line->parse();
        }

        return implode('', $html);
    }

    public function notReachedMethod()
    {
        return null;
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
     * @return BlockInterface|string|null
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
