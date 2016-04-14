<?php

namespace Kisphp;

use Kisphp\Exceptions\BlockNotFoundException;

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
    public function create($type)
    {
        $className = $this->getClassNamespace($type);

        return new $className($this);
    }

    /**
     * @return array
     */
    protected function getAvailableNamespaces()
    {
        return [
            __NAMESPACE__ . '\\Blocks\\',
            __NAMESPACE__ . '\\Blocks\\Paragraph\\',
            __NAMESPACE__ . '\\Blocks\\Headers\\',
            __NAMESPACE__ . '\\Blocks\\Inline\\',
            __NAMESPACE__ . '\\Blocks\\Lists\\',
        ];
    }

    /**
     * @param string $type
     *
     * @throws BlockNotFoundException
     *
     * @return string
     */
    public function getClassNamespace($type)
    {
        $classNamespaces = $this->getAvailableNamespaces();

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
        return new DataObject($markdownContent);
    }

    /**
     * @return DataObject
     */
    public function getDataObject()
    {
        return static::$dataObject;
    }

    /**
     * @return MarkdownInterface
     */
    public static function createMarkdown()
    {
        $factory = new static();

        return new Markdown($factory);
    }

    /**
     * @param DataObjectInterface $dataObject
     *
     * @return RowTypeGuesser
     */
    public function createRowTypeGuesser(DataObjectInterface $dataObject)
    {
        return new RowTypeGuesser($dataObject, $this);
    }
}
