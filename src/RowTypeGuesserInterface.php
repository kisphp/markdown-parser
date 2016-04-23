<?php

namespace Kisphp;

interface RowTypeGuesserInterface
{
    /**
     * @param $lineNumber
     *
     * @throws Exceptions\BlockNotFoundException
     *
     * @return BlockInterface
     */
    public function getRowObjectByLineContent($lineNumber);
}
