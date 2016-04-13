<?php

namespace Kisphp\Blocks\Lists;

interface ListTreeInterface
{
    /**
     * @return int
     */
    public function getLevel();

    /**
     * @param int $level
     *
     * return $this
     */
    public function setLevel($level);

    /**
     * @return string
     */
    public function getContent();

    /**
     * @param string $content
     *
     * return $this
     */
    public function setContent($content);

    /**
     * @return array
     */
    public function getChildren();

    /**
     * @param ListTreeInterface $treeInterface
     *
     * @return $this
     */
    public function addChildren(ListTreeInterface $treeInterface);

    /**
     * @return string
     */
    public function getStartTag();

    /**
     * @return string
     */
    public function getEndTag();

    /**
     * @return string
     */
    public function parse();
}
