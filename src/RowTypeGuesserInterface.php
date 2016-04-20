<?php

namespace Kisphp;

use Kisphp\Exceptions\MethodNotFoundException;

interface RowTypeGuesserInterface
{
    /**
     * @param $lineNumber
     *
     * @throws Exceptions\BlockNotFoundException
     * @throws MethodNotFoundException
     *
     * @return BlockInterface
     */
    public function getRowObjectByLineContent($lineNumber);
}
