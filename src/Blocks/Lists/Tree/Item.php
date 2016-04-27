<?php

namespace Kisphp\Blocks\Lists\Tree;

class Item implements ItemInterface
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
     * @var string
     */
    protected $listType;

    /**
     * @var array
     */
    protected $children = [];

    /**
     * @var BuilderInterface
     */
    protected $builder;

    /**
     * @param BuilderInterface $builder
     */
    public function __construct(BuilderInterface $builder)
    {
        $this->builder = $builder;
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
     * @param ItemInterface $item
     */
    public function addClild(ItemInterface $item)
    {
        $this->children[] = $item;
    }

    /**
     * @return string
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
            $this->listType = static::LIST_TYPE_ORDERED;

            return $this->listType;
        }

        $this->listType = static::LIST_TYPE_UNORDERED;

        return $this->listType;
    }

    /**
     * @return string
     */
    public function parse()
    {
        $html = $this->builder->createListItemStartTag();
        $html .= $this->getCleanedConent();
        $html .= $this->parseChildren();
        $html .= $this->builder->createListItemEndTag();

        return $html;
    }

    /**
     * @return string
     */
    protected function parseChildren()
    {
        $html = '';
        if (count($this->getChildren()) > 0) {
            /** @var ItemInterface $firstChild */
            $firstChild = $this->getFirstChild();
            $html .= $this->builder->createListStartTag($firstChild);
            /** @var ItemInterface $child */
            foreach ($this->getChildren() as $child) {
                $html .= $child->parse();
            }
            $html .= $this->builder->createListEndTag($firstChild);
        }

        return $html;
    }

    /**
     * @return ItemInterface
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
