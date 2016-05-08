<?php

namespace Kisphp;

use Kisphp\Exceptions\BlockNotFoundException;
use Kisphp\Exceptions\ParameterNotAllowedException;

class MarkdownFactory implements MarkdownFactoryInterface
{
    /**
     * @var DataObjectInterface
     */
    protected $dataObject;

    /**
     * @var array
     */
    protected $blockPlugins = [];

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
     * @return DataObjectInterface
     */
    public function getDataObject()
    {
        return $this->dataObject;
    }

    /**
     * @param DataObjectInterface $dataObject
     *
     * @return $this
     */
    public function setDataObject(DataObjectInterface $dataObject)
    {
        $this->dataObject = $dataObject;

        return $this;
    }

    /**
     * @return array
     */
    public function getBlockPlugins()
    {
        return $this->blockPlugins;
    }

    /**
     * @param string $firstLetter
     * @param string $blockName
     *
     * @throws ParameterNotAllowedException
     *
     * @return $this
     */
    public function addBlockPlugin($firstLetter, $blockName)
    {
        if (!is_string($blockName)) {
            throw new ParameterNotAllowedException('$blockName should be type of string');
        }

        if (!isset($this->blockPlugins[$firstLetter]) || !in_array($blockName, $this->blockPlugins[$firstLetter])) {
            $this->blockPlugins[$firstLetter][] = $blockName;
        }

        return $this;
    }

    /**
     * @param string $firstLetter
     * @param array $blockNameCollection
     *
     * @return $this
     */
    public function addBlockPlugins($firstLetter, array $blockNameCollection)
    {
        if (isset($this->blockPlugins[$firstLetter])) {
            $mergedPlugins = array_merge(
                $this->blockPlugins[$firstLetter],
                $blockNameCollection
            );

            $this->blockPlugins[$firstLetter] = array_unique($mergedPlugins);

            return $this;
        }

        $this->blockPlugins[$firstLetter] = $blockNameCollection;

        return $this;
    }

    /**
     * @return array
     */
    protected function getAvailableNamespaces()
    {
        return [
            __NAMESPACE__ . '\\Blocks\\',
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
        $mapping = [
            'BlockParagraph' => __NAMESPACE__ . '\\Blocks\\BlockParagraph',
            'BlockEmpty' => __NAMESPACE__ . '\\Blocks\\BlockEmpty',
            'BlockContinue' => __NAMESPACE__ . '\\Blocks\\BlockContinue',
            'BlockSkip' => __NAMESPACE__ . '\\Blocks\\BlockSkip',
            'BlockEmphasis' => __NAMESPACE__ . '\\Blocks\\Inline\\BlockEmphasis',
            'BlockStrongItalic' => __NAMESPACE__ . '\\Blocks\\Inline\\BlockStrongItalic',
            'BlockItalic' => __NAMESPACE__ . '\\Blocks\\Inline\\BlockItalic',
            'BlockStrong' => __NAMESPACE__ . '\\Blocks\\Inline\\BlockStrong',
            'BlockStrikethrough' => __NAMESPACE__ . '\\Blocks\\Inline\\BlockStrikethrough',
            'BlockImage' => __NAMESPACE__ . '\\Blocks\\BlockImage',
            'BlockUrls' => __NAMESPACE__ . '\\Blocks\\BlockUrls',
            'BlockCodeInjector' => __NAMESPACE__ . '\\Blocks\\Inline\\BlockCodeInjector',
        ];

        if (isset($mapping[$type])) {
            return $mapping[$type];
        }

        $classNamespaces = $this->getAvailableNamespaces();

        foreach ($classNamespaces as $namespace) {
            $className = $namespace . $type;

            if (class_exists($className)) {
                return $className;
            }
        }

        throw new BlockNotFoundException('Block ' . $type . ' not found');
    }

    /**
     * @param $markdownContent
     *
     * @return DataObjectInterface
     */
    public function createDataObject($markdownContent)
    {
        return new DataObject($markdownContent);
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
     * @todo create separate plugin to be loaded in the project
     *
     * extend this method to add your own custom inline markup
     *
     * @param string $lineContent
     *
     * @return string
     */
    public function parseCustomInlineMarkup($lineContent)
    {
        return $lineContent;
    }
}
