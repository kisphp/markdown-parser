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

    /**
     * @param int $lineNumber
     *
     * @return bool
     */
    public function isBlockList($lineNumber);

    /**
     * @param string $lineContent
     *
     * @return bool
     */
    public static function isBlockUnorderedListByContent($lineContent);

    /**
     * @param string $lineContent
     *
     * @return bool
     */
    public static function isBlockOrderedListByContent($lineContent);

    /**
     * @param int $lineNumber
     *
     * @return bool
     */
    public function isBlockHeader($lineNumber);

    /**
     * @param int $lineNumber
     *
     * @return bool
     */
    public function isBlockContinue($lineNumber);

    /**
     * @param int $lineNumber
     *
     * @return bool
     */
    public function isBlockHeaderOne($lineNumber);

    /**
     * @param int $lineNumber
     *
     * @return bool
     */
    public function isBlockHeaderTwo($lineNumber);

    /**
     * @param int $lineNumber
     *
     * @return bool
     */
    public function isBlockHorizontalRule($lineNumber);

    /**
     * @param int $lineNumber
     *
     * @return bool
     */
    public function isBlockCode($lineNumber);

    /**
     * @param int $lineNumber
     *
     * @return bool
     */
    public function isBlockQuote($lineNumber);
}
