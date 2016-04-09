<?php

namespace Kisphp;

use Kisphp\Exceptions\BlockNotFoundException;
use Kisphp\Interfaces\BlockFactoryInterface;
use Kisphp\Interfaces\BlockInterface;

class BlockFactory implements BlockFactoryInterface
{
    /**
     * @var DataObject
     */
    private static $dataObject;

    /**
     * @param string $type
     *
     * @throws BlockNotFoundException
     *
     * @return BlockInterface
     */
    public static function create($type)
    {
        $className = self::getClassNamespace($type);

        return new $className();
    }

    /**
     * @return array
     */
    protected static function getAvailableNamespaces()
    {
        return [
            __NAMESPACE__ . '\\Blocks\\',
            __NAMESPACE__ . '\\Blocks\\Paragraph\\',
            __NAMESPACE__ . '\\Blocks\\Headers\\',
            __NAMESPACE__ . '\\Blocks\\Inline\\',
        ];
    }

    /**
     * @param string $type
     *
     * @throws BlockNotFoundException
     *
     * @return string
     */
    public static function getClassNamespace($type)
    {
        $classNamespaces = static::getAvailableNamespaces();

        foreach ($classNamespaces as $namespace) {
            $className = $namespace . $type;

            if (class_exists($className)) {
                return $className;
            }
        }

        throw new BlockNotFoundException($type);
    }

    /**
     * @param $markdownContent
     *
     * @return DataObject
     */
    public function createDataObject($markdownContent)
    {
        if (static::$dataObject === null) {
            static::$dataObject = new DataObject($markdownContent);
        }

        return static::$dataObject;
    }

    /**
     * @return DataObject
     */
    public function getDataObject()
    {
        return static::$dataObject;
    }
}
