<?php

namespace Kisphp\Blocks\Lists\Tree;

class Item
{
    const LIST_TYPE_UNORDERED = 'ul';
    const LIST_TYPE_ORDERED = 'ol';

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
     * @var
     */
    protected $listType;

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
        $this->setListTypeByContent();
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
     * @return mixed
     */
    public function getListType()
    {
        return $this->listType;
    }

    /**
     * @return string
     */
    protected function setListTypeByContent()
    {
        $content = trim($this->content);

        if (preg_match('/^[0-9]\.\s/', $content)) {
            $this->listType = self::LIST_TYPE_ORDERED;

            return $this->listType;
        }

        $this->listType = self::LIST_TYPE_UNORDERED;

        return $this->listType;
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
            /** @var Item $firstChild */
            $firstChild = $this->getFirstChild();
            $html .= '<' . $firstChild->getListType() . '>' . "\n";
            /** @var Item $child */
            foreach ($this->getChildren() as $child) {
                $html .= $child->parse();
            }
            $html .= '</' . $firstChild->getListType() . '>' . "\n";
        }

        return $html;
    }

    /**
     * @return Item
     */
    protected function getFirstChild()
    {
        return reset($this->children);
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
