<?php

namespace Kisphp\Blocks;

use Kisphp\AbstractBlock;

class BlockHorizontalRule extends AbstractBlock
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
     * @return string
     */
    public function getEndTag()
    {
        return '<hr />' . "\n";
    }

    /**
     * @param int $lineNumber
     *
     * @return bool
     */
    public function validateLineType($lineNumber)
    {
        $dataObject = $this->factory->getDataObject();

        return (bool) preg_match('/^([\*|\*\s|\-|\-\s|\_|\_\s]{3,})/', $dataObject->getLine($lineNumber));
    }
}
