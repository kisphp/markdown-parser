<?php

namespace Kisphp\Blocks;

use Kisphp\AbstractBlock;
use Kisphp\DataObjectInterface;

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

    public static function validateLineType($lineNumber, DataObjectInterface $dataObject)
    {
        return (bool) preg_match('/^([\*|\*\s|\-|\-\s|\_|\_\s]{3,})/', $dataObject->getLine($lineNumber));
    }
}
