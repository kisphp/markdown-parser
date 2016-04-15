<?php

namespace Kisphp;

use Kisphp\Exceptions\BlockNotFoundException;

interface BlockFactoryInterface
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
