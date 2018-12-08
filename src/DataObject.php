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
     * @var array
     */
    protected $references = [];

    /**
     * @param string $markdownContent
     */
    public function __construct($markdownContent)
    {
        $markdownContent = $this->cleanupContent($markdownContent);

        $this->lines = explode("\n", $markdownContent);
    }

    /**
     * @param int|string $key
     * @param array $dataMapping
     *
     * @return $this
     */
    public function addReference($key, array $dataMapping)
    {
        $this->references[$key] = $dataMapping;

        return $this;
    }

    /**
     * @param int|string $key
     *
     * @return array
     */
    public function getReferenceByKey($key)
    {
        return $this->references[$key];
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
     * @return string
     */
    public function parseEachLine()
    {
        $html = '';
        /** @var BlockInterface $line */
        foreach ($this->lines as $line) {
            $html .= $line->parse();
        }
        $html = $this->replaceReferenceUrls($html);

        return $html;
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
     * @return null|BlockInterface|string
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
     * @param string $html
     *
     * @return string
     */
    protected function replaceReferenceUrls($html)
    {
        return preg_replace_callback('/\[(.*)\]\[(.*)\]/U', function ($found) {
            $key = trim($found[2]);
            $label = trim($found[1]);

            if (empty($key)) {
                $key = $label;
            }

            if (isset($this->references[$key]) === false) {
                return $found[0];
            }

            $reference = $this->getReferenceByKey($key);

            return $this->convertReference($reference, $label);
        }, $html);
    }

    /**
     * @param array $dataMapping
     * @param string $label
     *
     * @return string
     */
    protected function convertReference(array $dataMapping, $label)
    {
        return '<a href="' . $dataMapping['url'] . '" title="' . $dataMapping['title'] . '">' . $label . '</a>';
    }
}
