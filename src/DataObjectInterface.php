<?php

namespace Kisphp;

use Kisphp\Exceptions\DataObjectBlockAlreadyExists;

interface DataObjectInterface
{
    /**
     * @return int
     */
    public function count();

    /**
     * @return string
     */
    public function parseEachLine();

    /**
     * @param int $lineNumber
     *
     * @return bool
     */
    public function hasLine($lineNumber);

    /**
     * @param int $lineNumber
     *
     * @return null|BlockInterface|string
     */
    public function getLine($lineNumber);

    /**
     * @param int $key
     * @param BlockInterface $value
     *
     * @return $this
     */
    public function updateLine($key, BlockInterface $value);

    /**
     * @param string $blockUniqueKey
     * @param string $renderedBlockContent
     *
     * @throws DataObjectBlockAlreadyExists
     *
     * @return $this
     */
    public function saveAvailableBlock($blockUniqueKey, $renderedBlockContent);

    /**
     * @param string $blockKey
     *
     * @return string
     */
    public function getBlockByKey($blockKey);

    /**
     * @param string $key
     * @param array $dataMapping
     *
     * @return $this
     */
    public function addReference($key, array $dataMapping);
}
