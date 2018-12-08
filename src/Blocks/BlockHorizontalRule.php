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
        $lineContent = $dataObject->getLine($lineNumber);

        $contentTrimmed = str_replace(['*', '-', '_'], '', $lineContent);
        if (!empty(trim($contentTrimmed))) {
            return false;
        }

        return (bool) preg_match('/^([\*|\*\s|\-|\-\s|\_|\_\s]{3,})/', $lineContent);
    }
}
