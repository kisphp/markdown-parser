<?php

namespace Kisphp\Blocks;

use Kisphp\AbstractBlock;
use Kisphp\AbstractBlockNoParse;
use Kisphp\BlockInterface;
use Kisphp\BlockTypes;
use Kisphp\DataObjectInterface;

class BlockTable extends AbstractBlockNoParse
{
    /**
     * @var array
     */
    protected $tableMetaData = [];

    /**
     * @return string
     */
    public function getStartTableTag()
    {
        return '<table>' . "\n";
    }

    /**
     * @return string
     */
    public function getEndTableTag()
    {
        return '</table>';
    }

    /**
     * @param DataObjectInterface $dataObject
     */
    public function changeLineType(DataObjectInterface $dataObject)
    {
        $max = $dataObject->count();
        $changeNextLine = true;
        $firstLineCompiled = false;

        $htmlTable = '';

        for ($i = $this->lineNumber; $i < $max; $i++) {
            $currentLine = $dataObject->getLine($i);

            $htmlTable .= $this->createTableRow($currentLine);

            if ($firstLineCompiled === false) {
                $firstLineCompiled = true;
                $previousLineIndex = $i - 1;
                $previousLine = $dataObject->getLine($previousLineIndex);
                $htmlTable = $this->createTableRow($previousLine, true) . $htmlTable;
                $this->createSkipLine($dataObject, $previousLineIndex);
                unset($previousLineIndex);
            }

            $this->createSkipLine($dataObject, $i);

            /** @var AbstractBlock $nextLineObject */
            $nextLineObject = $dataObject->getLine($i + 1);
            if (!$this->isTableLineType($nextLineObject)) {
                $changeNextLine = false;
            }

            if ($changeNextLine === false) {
                break;
            }
        }

        $htmlTable = $this->parseInlineMarkup($htmlTable);

        $listContent = $this->factory->create(BlockTypes::BLOCK_UNCHANGE)
            ->setContent($this->getStartTableTag() . $htmlTable . $this->getEndTableTag())
            ->setLineNumber($this->lineNumber)
        ;

        $dataObject->updateLine($this->lineNumber, $listContent);
    }

    /**
     * @param DataObjectInterface $dataObject
     * @param int $lineNumber
     */
    protected function createSkipLine(DataObjectInterface $dataObject, $lineNumber)
    {
        $changedContent = $this->factory->create(BlockTypes::BLOCK_SKIP);
        $dataObject->updateLine($lineNumber, $changedContent);
    }

    /**
     * @param BlockInterface $block
     *
     * @return string
     */
    protected function createTableRow(BlockInterface $block, $isHeader = false)
    {
        $lineContent = trim($block->getContent(), '|');

        if (strpos($lineContent, '---') !== false) {
            return '';
        }

        $tableRow = explode('|', $lineContent);

        $rowHtml = $this->getTableRowStartTag();

        foreach ($tableRow as $rowIndex => $row) {
            $rowHtml .= $this->getTableColumnStartTag($isHeader);
            $rowHtml .= trim($row);
            $rowHtml .= $this->getTableColumnEndTag($isHeader);
        }

        $rowHtml .= $this->getTableRowEndTag();

        return $rowHtml;
    }

    /**
     * @return string
     */
    protected function getTableRowStartTag()
    {
        return '<tr>';
    }

    /**
     * @return string
     */
    protected function getTableRowEndTag()
    {
        return '</tr>' . "\n";
    }

    /**
     * @param bool|false $isTableHeader
     *
     * @return string
     */
    protected function getTableColumnStartTag($isTableHeader = false)
    {
        if ($isTableHeader === true) {
            return '<th>';
        }

        return '<td>';
    }

    /**
     * @param bool|false $isTableHeader
     *
     * @return string
     */
    protected function getTableColumnEndTag($isTableHeader = false)
    {
        if ($isTableHeader === true) {
            return '</th>' . "\n";
        }

        return '</td>' . "\n";
    }

    /**
     * @param BlockInterface $block
     *
     * @return bool
     */
    protected function isTableLineType(BlockInterface $block = null)
    {
        if ($block === null) {
            return false;
        }

        return ($this->lineIsObjectOf($block, static::class) || strpos($block->getContent(), '|') !== false);
    }
}
