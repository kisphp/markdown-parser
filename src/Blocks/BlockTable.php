<?php

namespace Kisphp\Blocks;

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
     * @return string
     */
    protected function getTableRowStartTag()
    {
        return '<tr>' . "\n";
    }

    /**
     * @return string
     */
    protected function getTableRowEndTag()
    {
        return '</tr>' . "\n";
    }

    /**
     * @param int $rowIndex
     * @param bool|false $isTableHeader
     *
     * @return string
     */
    protected function getTableColumnStartTag($rowIndex, $isTableHeader = false)
    {
        $rowMetaData = $this->getCompiledRowMetaData($rowIndex);

        if ($isTableHeader === true) {
            return '<th' . $rowMetaData . '>';
        }

        return '<td' . $rowMetaData . '>';
    }

    /**
     * @param int $rowIndex
     *
     * @return string
     */
    protected function getCompiledRowMetaData($rowIndex)
    {
        $rowMetaData = '';
        if (empty($this->tableMetaData[$rowIndex])) {
            return $rowMetaData;
        }

        foreach ($this->tableMetaData[$rowIndex] as $attributeName => $attributeValue) {
            $rowMetaData .= sprintf(' %s="%s"', $attributeName, $attributeValue);
        }

        return $rowMetaData;
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

            /** @var BlockInterface $nextLineObject */
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
     * @param BlockInterface $block
     *
     * @return string
     */
    protected function createTableRow(BlockInterface $block, $isHeader = false)
    {
        $lineContent = trim($block->getContent(), '|');
        if (empty($lineContent)) {
            return '';
        }

        $tableRow = explode('|', $lineContent);

        if (strpos($lineContent, '---') !== false) {
            foreach ($tableRow as $rowIndex => $row) {
                $this->setColumnMetadata($rowIndex, $row);
            }

            return '';
        }

        $rowHtml = $this->getTableRowStartTag();

        foreach ($tableRow as $rowIndex => $row) {
            $rowHtml .= $this->getTableColumnStartTag($rowIndex, $isHeader);
            $rowHtml .= trim($row);
            $rowHtml .= $this->getTableColumnEndTag($isHeader);
        }

        $rowHtml .= $this->getTableRowEndTag();

        return $rowHtml;
    }

    /**
     * @param int $rowIndex
     * @param string $columnContent
     */
    protected function setColumnMetadata($rowIndex, $columnContent)
    {
        if (strpos($columnContent, ':-') !== false && strpos($columnContent, '-:') !== false) {
            $this->tableMetaData[$rowIndex] = $this->createColumnMetaDataArray('center');

            return;
        }

        if (strpos($columnContent, ':-') !== false) {
            $this->tableMetaData[$rowIndex] = $this->createColumnMetaDataArray('left');

            return;
        }

        if (strpos($columnContent, '-:') !== false) {
            $this->tableMetaData[$rowIndex] = $this->createColumnMetaDataArray('right');

            return;
        }

        return;
    }

    /**
     * @param string $alignment
     *
     * @return array
     */
    protected function createColumnMetaDataArray($alignment)
    {
        return [
            'style' => 'text-align: ' . $alignment,
        ];
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

    /**
     * @param int $lineNumber
     *
     * @return bool
     */
    public function validateLineType($lineNumber)
    {
        $dataObject = $this->factory->getDataObject();
        $lineContent = $dataObject->getLine($lineNumber);
        $lineContent = trim($lineContent);

        if (strpos($lineContent, '|') !== false && strpos($lineContent, '---') !== false) {
            return true;
        }

        return false;
    }
}
