<?php

namespace Kisphp;

abstract class AbstractBlockNoParse extends AbstractBlock
{
    /**
     * @return string
     */
    public function parse()
    {
        return $this->getStartTag() . $this->getEndTag();
    }

    /**
     * @return string|null
     */
    public function getStartTag()
    {
        return null;
    }

    /**
     * @return string|null
     */
    public function getEndTag()
    {
        return null;
    }
}
