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
     * @return null
     */
    public function getStartTag()
    {
        return null;
    }

    /**
     * @return null
     */
    public function getEndTag()
    {
        return null;
    }
}
