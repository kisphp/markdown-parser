<?php

namespace Kisphp\Blocks\Inline;

use Kisphp\AbstractBlock;

class BlockCodeInjector extends AbstractBlock
{
    /**
     * @return string
     */
    public function parse()
    {
        return preg_replace_callback('/::(.*)::/U', function ($found) {

            $content = $this->factory
                ->getDataObject()
                ->getBlockByKey($found[1])
            ;

            return $this->getStartTag() . $content . $this->getEndTag();

        }, $this->content);
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
