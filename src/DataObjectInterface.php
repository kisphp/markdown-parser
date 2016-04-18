<?php

namespace Kisphp;

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
     * @return mixed
     */
    public function getLines();

    /**
     * @param int $lineNumber
     *
     * @return bool
     */
    public function hasLine($lineNumber);

    /**
     * @param int $lineNumber
     *
     * @return BlockInterface|null
     */
    public function getLine($lineNumber);

    /**
     * @param int $key
     * @param BlockInterface $value
     *
     * @return $this
     */
    public function updateLine($key, BlockInterface $value);
}
