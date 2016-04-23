<?php

namespace Kisphp\Blocks;

use Kisphp\AbstractBlockNoParse;

class BlockContinue extends AbstractBlockNoParse
{
    /**
     * @param int $lineNumber
     *
     * @return bool
     */
    public function validateLineType($lineNumber)
    {
        if ($lineNumber < 1) {
            return false;
        }

        return (bool) preg_match('/^([\s]{1,}|[\t]+)\s/', $this->factory->getDataObject()->getLine($lineNumber));
    }
}
