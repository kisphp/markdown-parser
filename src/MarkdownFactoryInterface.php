<?php

namespace Kisphp;

use Kisphp\Exceptions\BlockNotFoundException;

interface MarkdownFactoryInterface
{
    /**
     * @param string $type
     *
     * @throws BlockNotFoundException
     *
     * @return BlockInterface
     */
    public function create($type);

    /**
     * @param string $type
     *
     * @throws BlockNotFoundException
     *
     * @return string
     */
    public function getClassNamespace($type);

    /**
     * @return array
     */
    public function getBlockPlugins();

    /**
     * @return DataObjectInterface
     */
    public function getDataObject();

    /**
     * @param DataObjectInterface $dataObject
     *
     * @return $this
     */
    public function setDataObject(DataObjectInterface $dataObject);

    /**
     * @param string $firstLetter
     * @param string $blockName
     *
     * @return $this
     */
    public function addBlockPlugin($firstLetter, $blockName);

    /**
     * @param string $firstLetter
     * @param array $blockNameCollection
     *
     * @return $this
     */
    public function addBlockPlugins($firstLetter, array $blockNameCollection);

    /**
     * @param $markdownContent
     *
     * @return DataObjectInterface
     */
    public function createDataObject($markdownContent);

    /**
     * @return MarkdownInterface
     */
    public static function createMarkdown();

    /**
     * @return RowTypeGuesserInterface
     */
    public function createRowTypeGuesser(DataObjectInterface $objectInterface);
}
