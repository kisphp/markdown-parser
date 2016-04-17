<?php

namespace Kisphp\Blocks\Lists\Tree;

class Item
{
    /**
     * @var string
     */
    protected $content;

    /**
     * @var int
     */
    protected $level;

    /**
     * @var int
     */
    protected $id;

    /**
     * @var array
     */
    protected $children = [];

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return int
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @param int $level
     */
    public function setLevel($level)
    {
        $this->level = $level;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return array
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param array $children
     */
    public function setChildren($children)
    {
        $this->children = $children;
    }

    /**
     * @param Item $item
     */
    public function addClild(Item $item)
    {
        $this->children[] = $item;
    }

    /**
     * @return string
     */
    public function parse()
    {
        $html = '<li>';
        $html .= $this->getCleanedConent();
        $html .= $this->parseChildren();
        $html .= '</li>' . "\n";

        return $html;
    }

    /**
     * @return string
     */
    protected function parseChildren()
    {
        $html = '';
        if (count($this->getChildren()) > 0) {
            $html .= '<ul>';
            /** @var Item $child */
            foreach ($this->getChildren() as $child) {
                $html .= $child->parse();
            }
            $html .= '</ul>';
        }

        return $html;
    }

    /**
     * @return string
     */
    protected function getCleanedConent()
    {
        $content = trim($this->content);
        $content = preg_replace('/(^[0-9]\.\s|^\*\s|^\-\s|^\+\s)/', '', $content);

        return trim($content);
    }
}
