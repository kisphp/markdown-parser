<?php

namespace Kisphp;

class AbstractBlockNoParse extends AbstractBlock
{
    /**
     * @return null
     */
    public function parse()
    {
        return null;
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