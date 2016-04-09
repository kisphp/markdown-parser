<?php

namespace Kisphp\Interfaces;

use Kisphp\DataObject;

interface BlockFactoryInterface
{
    /**
     * @param string $type
     *
     * @return BlockInterface
     */
    public static function create($type);

    /**
     * @param $markdownContent
     *
     * @return DataObject
     */
    public function createDataObject($markdownContent);

    /**
     * @return DataObject
     */
    public function getDataObject();
}
