<?php

namespace Kisphp\Blocks\Lists;

class ListTreeItem implements ListTreeInterface
{
    const TYPE_UL = 'ul';
    const TYPE_OL = 'ol';

    const LIST_MARKUP_PATTERNS = '/^([\*\s]{1}[\s?]|[\-\s]{1}[\s?]|[\+\s]{1}[\s?]|[\d]{1}\.[\s?])/';

    /**
     * @var int
     */
    protected $level = 0;

    /**
     * @var string
     */
    protected $content;

    /**
     * @var array
     */
    protected $children = [];

    /**
     * @var string
     */
    protected $listType = self::TYPE_UL;

    /**
     * @return bool
     */
    protected function hasChildren()
    {
        return (bool) count($this->children) > 0;
    }

    /**
     * @return string
     */
    public function getStartTag()
    {
        return '<' . $this->listType . '>';
    }

    /**
     * @return string
     */
    public function getEndTag()
    {
        return '</' . $this->listType . '>' . "\n";
    }

    /**
     * @return string
     */
    protected function getStartItemTag()
    {
        return '<li>';
    }

    /**
     * @return string
     */
    protected function getEndItemTag()
    {
        return '</li>' . "\n";
    }

    /**
     * @param string $content
     *
     * @return string
     */
    protected function clearListMarkup($content)
    {
        return preg_replace(self::LIST_MARKUP_PATTERNS, '', trim($content));
    }

    /**
     * @return string
     */
    public function parse()
    {
        $html = $this->getStartItemTag();
        $html .= $this->getRowContent();
        $html .= $this->getEndItemTag();

        return $html;
    }

    /**
     * @return string
     */
    protected function getRowContent()
    {
        $html = $this->clearListMarkup($this->content);
        $html .= $this->parseChildren();

        return $html;
    }

    /**
     * @return string
     */
    protected function parseChildren()
    {
        $html = '';
        if ($this->hasChildren()) {
            /** @var ListTreeInterface $fistElement */
            $fistElement = $this->getChildren()[0];

            $html .= $fistElement->getStartTag();

            /** @var ListTreeInterface $child */
            foreach ($this->getChildren() as $child) {
                $html .= $child->parse();
            }

            $html .= $fistElement->getEndTag();

            return $html;
        }

        return $html;
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
     * @return array
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param ListTreeInterface $treeInterface
     *
     * @return $this
     */
    public function addChildren(ListTreeInterface $treeInterface)
    {
        $this->children[] = $treeInterface;

        return $this;
    }

    /**
     * @return string
     */
    public function getListType()
    {
        return $this->listType;
    }

    /**
     * @param string $listType
     */
    public function setListType($listType)
    {
        $this->listType = $listType;
    }
}
