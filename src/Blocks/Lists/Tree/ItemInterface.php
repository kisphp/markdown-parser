<?php

namespace Kisphp\Blocks\Lists\Tree;

interface ItemInterface
{
    /**
     * @param string $content
     */
    public function setContent($content);

    /**
     * @param int $level
     */
    public function setLevel($level);

    /**
     * @return int
     */
    public function getId();

    /**
     * @param int $id
     */
    public function setId($id);

    /**
     * @return array
     */
    public function getChildren();

    /**
     * @param ItemInterface $item
     */
    public function addClild(ItemInterface $item);

    /**
     * @return string
     */
    public function getListType();

    /**
     * @return string
     */
    public function parse();
}
