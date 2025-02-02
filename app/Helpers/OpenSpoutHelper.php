<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Date;
use OpenSpout\Common\Entity\Cell;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Common\Entity\Style\Border;
use OpenSpout\Common\Entity\Style\BorderPart;
use OpenSpout\Common\Entity\Style\CellAlignment;
use OpenSpout\Common\Entity\Style\Color;
use OpenSpout\Common\Entity\Style\Style;
use OpenSpout\Reader\XLSX\Reader;
use OpenSpout\Writer\XLSX\Options;
use OpenSpout\Writer\XLSX\Writer;

class OpenSpoutHelper
{
    protected array $columnHeaderStyles = [];
    protected array $columnDetailStyles = [];
    protected Options $options;

    public function readFileExcel(string $filePath, int $skipRow = 1, $sheetName = null, bool $useFirstRowAsKeyName = false): array
    {
        // variables
        $data = [];
        $skipIndex = $skipRow;
        $columns = [];

        // initialize reader
        $reader = new Reader();
        $reader->open($filePath);

        // Loop sheet
        foreach ($reader->getSheetIterator() as $sheet) {
            // if $sheetName is not empty
            if (!empty($sheetName)) {
                // if sheet name = $sheetName
                if ($sheet->getName() === $sheetName) {
                    // loop the rows in sheet
                    foreach ($sheet->getRowIterator() as $index => $row) {
                        // if $useFirstRowAsKeyName = true
                        if ($useFirstRowAsKeyName && $index === 1) {
                            // append values to $columns
                            $columns = array_map(function ($cell) {
                                return str($cell->getValue())->lower()->slug('_')->toString();
                            }, $row->getCells());
                        }

                        // if index <= $skipIndex then continue
                        if ($index <= $skipIndex) continue;

                        // append values to $data
                        $data[] = array_map(function ($cell) {
                            return $cell->getValue();
                        }, $row->getCells());
                    }

                    // stop looping after done
                    break;
                }

                // stop looping
                break;
            } else {
                // create new index key use sheet name, lower case and replace the space with underscore
                $newIndexKey = str($sheet->getName())->lower()->slug('_')->toString();

                // loop the rows in sheet
                foreach ($sheet->getRowIterator() as $index => $row) {
                    // if $useFirstRowAsKeyName = true
                    if ($useFirstRowAsKeyName && $index === 1) {
                        // append values to $columns
                        $columns = array_map(function ($cell) {
                            return str($cell->getValue())->lower()->slug('_')->toString();
                        }, $row->getCells());
                    }

                    // if index <= $skipIndex then continue
                    if ($index <= $skipIndex) continue;

                    // append values to $data
                    $data[$newIndexKey][] = array_map(function ($cell) {
                        return $cell->getValue();
                    }, $row->getCells());
                }
            }
        }

        $reader->close();

        // if $useFirstRowAsKeyName = true
        if ($useFirstRowAsKeyName) {
            $data = $this->refactorDataAndKeyName($columns, $data);
        }

        return $data;
    }

    private function refactorDataAndKeyName(array $columns, array $rows)
    {
        // variables
        $newData = [];

        // loop data
        foreach ($rows as $row) {
            // temporary
            $tmp = [];

            // loop columns
            foreach ($columns as $index => $column) {
                // append
                $tmp[$column] = $row[$index];
            }

            // push to new data
            $newData[] = $tmp;

            // clear
            unset($tmp);
        }

        return $newData;
    }

    public function generateNewSpreadsheet(string $filePath, string $sheetName = "DATA", ?bool $useClose = false): Writer
    {
        // options
        $this->options = new Options();
        $this->options->SHOULD_CREATE_NEW_SHEETS_AUTOMATICALLY = true;

        // initialize new writer
        $writer = new Writer($this->options);

        // open the file
        $writer->openToFile($filePath);

        // rename sheet
        $writer->getCurrentSheet()->setName($sheetName);

        if ($useClose) {
            $writer->close();
            if (ob_get_contents() || ob_get_length() > 0) ob_end_clean();
        } else {
            return $writer;
        }
    }

    public function closeWriter(Writer $writer)
    {
        if (!empty($writer)) {
            $writer->close();
            if (ob_get_contents() || ob_get_length() > 0) ob_end_clean();
        }
    }

    private function getBorderStyle(?string $borderPosition = 'tlrb'): Border
    {
        // variables
        $borders = [];

        // top
        if (str($borderPosition)->contains('t')) {
            $borders[] = new BorderPart(Border::TOP, Color::BLACK,  Border::WIDTH_THIN, Border::STYLE_SOLID);
        }

        // left
        if (str($borderPosition)->contains('l')) {
            $borders[] = new BorderPart(Border::LEFT, Color::BLACK,  Border::WIDTH_THIN, Border::STYLE_SOLID);
        }

        // right
        if (str($borderPosition)->contains('r')) {
            $borders[] = new BorderPart(Border::RIGHT, Color::BLACK,  Border::WIDTH_THIN, Border::STYLE_SOLID);
        }

        // bottom
        if (str($borderPosition)->contains('r')) {
            $borders[] = new BorderPart(Border::BOTTOM, Color::BLACK,  Border::WIDTH_THIN, Border::STYLE_SOLID);
        }

        return new Border(...$borders);
    }

    public function getStyle(string $align = 'left', ?bool $useBold = false, ?string $formatColumn = null, ?bool $useBordered = true, ?bool $wrapText = false, ?int $fontSize = 12, ?string $fontColor = '000000', ?string $backgroundColor = null, ?bool $useItalic = false, ?bool $useUnderline = false): Style
    {
        // initialize style
        $style = new Style();

        if ($useBold) $style = $style->setFontBold();
        if ($useItalic) $style = $style->setFontItalic();
        if ($useUnderline) $style = $style->setFontUnderline();
        if ($fontSize != 12) $style = $style->setFontSize($fontSize);
        if ($useBordered) $style = $style->setBorder($this->getBorderStyle());
        if ($formatColumn != null) $style = $style->setFormat($formatColumn);
        if ($wrapText) $style = $style->setShouldWrapText();

        // align
        switch ($align) {
            case 'center':
                $style->setCellAlignment(CellAlignment::CENTER);
                break;
            case 'right':
                $style->setCellAlignment(CellAlignment::RIGHT);
                break;
            case 'left':
            default:
                $style->setCellAlignment(CellAlignment::LEFT);
                break;
        }

        // colors
        $style->setFontColor(Color::toARGB($fontColor));

        // background
        if ($backgroundColor != null) {
            $style->setBackgroundColor(Color::toARGB($backgroundColor));
        }

        return $style;
    }

    public function getFormatColumn(string $type = null)
    {
        switch ($type) {
            case 'string':
                return '@';
                break;

            case 'datetime':
                return config('setting.local.js_datetime_format');
                break;

            case 'date':
                return config('setting.local.js_date_format');
                break;

            case 'time':
                return config('setting.local.js_time_format');
                break;

            case 'integer':
                return config('setting.local.jasper_format_integer');
                break;

            case 'number':
                $formatCode = config('setting.local.jasper_format_integer');
                $precision_length = config('setting.local.numeric_precision_length');
                if ($precision_length > 0) {
                    $formatCode = config('setting.local.jasper_format_integer') . '.' . str_repeat("0", $precision_length);
                }
                return $formatCode;
                break;

            case 'general':
            default:
                return null;
                break;
        }
    }

    private function printToCell(mixed $val, string $type, Style $style): Cell
    {
        if (!empty($val)) {
            switch ($type) {
                case 'number':
                    $val = floatval($val);
                    break;
                case 'integer':
                    $val = intval($val);
                    break;
                case 'string':
                    $val = (string)$val;
                    break;
                case 'datetime':
                case 'date':
                case 'time':
                    $val = Date::parse($val);
                    break;
            }
        } else {
            $val = (string) "";
        }

        return Cell::fromValue($val, $style);
    }

    private function num2alpha($n): string
    {
        $n = $n - 1;

        $r = '';
        for ($i = 1; $n >= 0 && $i < 10; $i++) {
            $r = chr(0x41 + ($n % pow(26, $i) / pow(26, $i - 1))) . $r;
            $n -= pow(26, $i);
        }
        return $r;
    }

    public function fillDataSheet(Writer $writer, array $records, array $columns, ?array $titles = [], ?bool $useNumberFirstRow = false, ?bool $sumFooter = false, ?array $sumFooterOptions = [], ?bool $writeColumnHeader = true, ?int $startCol = 1, ?int $startRow = 1)
    {
        // array types that do not need to check  Array tipe data yang tidak perlu di cek
        $excludeArrayTypes = ['serial'];

        // variables
        $cells = [];
        $lastRow = $startRow;
        $lastCol = $startCol;

        // create style for column header
        $this->columnHeaderStyles = array_map(function ($column) {
            return $this->getStyle(align: $column['align'], useBold: true);
        }, $columns);

        // create style for column detail
        $this->columnDetailStyles = array_map(function ($column) {
            return $this->getStyle(align: $column['align'], useBold: false, formatColumn: $this->getFormatColumn($column['type']));
        }, $columns);

        // if $useNumberFirstRow = true, then loop count total $headers and print numbers
        if ($useNumberFirstRow) {
            // create custom style
            $customStyle = $this->getStyle(align: 'center', useBold: false, useBordered: false);

            // for loop count $columns
            for ($i = 1; $i <= count($columns); $i++) {
                // push to cells
                $cells[] = $lastCol;
                $lastCol++;
            }

            // add new row
            $writer->addRow(Row::fromValues($cells, $customStyle));

            // reset col index
            $lastCol = $startCol;
        }

        // clear cells
        $cells = [];

        // looping titles
        if (!empty($titles)) {
            // if $useNumberFirstRow = true then add 1
            $lastRow = $useNumberFirstRow ? ($startRow + 1) : $startRow;

            // loop titles
            foreach ($titles as $txt) {
                // push cell
                $cells[] = Cell::fromValue($txt);

                // add row index
                $lastRow++;
            }

            // add new row
            $writer->addRow((new Row($cells)));

            // merge cell
            $this->options->mergeCells(0, ($lastRow - 1), (count($columns) - 1), ($lastRow - 1), $writer->getCurrentSheet()->getIndex());
        }

        // reset col index
        $lastCol = $startCol;

        // clear cells
        $cells = [];

        // looping column header
        if ($writeColumnHeader) {
            // loop columns
            foreach ($columns as $key => $value) {
                // push to cells
                $cells[] = Cell::fromValue($value['text'], $this->columnHeaderStyles[$key]);
                $lastCol++;
            }

            // add new row
            $writer->addRow((new Row($cells)));

            // clear cells
            $cells = [];

            // reset col index
            $lastCol = $startCol;

            // add row index
            $lastRow++;
        }

        // for footer sum
        $sumStartRow = $lastRow + ($useNumberFirstRow ? 1 : 0);

        // prepare loop data
        $serial = 1;
        $recordCount = count($records);

        // if recordCount > 0
        if ($recordCount > 0) {
            // looping data
            foreach ($records as $key => $record) {
                // reset col index
                $lastCol = $startCol;

                // clear cells
                $cells = [];

                // convert to array
                $record = is_array($record) ? $record : (array) $record;

                // looping data to right use columns
                foreach ($columns as $key => $value) {
                    // default style
                    $style = $this->columnDetailStyles[$key];

                    // if array key not exist then continue
                    if (!array_key_exists($key, $record) && !in_array($value['type'], $excludeArrayTypes)) continue;

                    // get value
                    if (!in_array($value['type'], $excludeArrayTypes)) {
                        $val = $record[$key];
                    }

                    // if column type is function
                    if ($value['type'] == 'function') {
                        // call function and replace value
                        $val = call_user_func_array([$this, $value['function_name']], [$val, $value['function_params']]);

                        // change type
                        $value['type'] = $value['return_type'];
                    }

                    // if column type is serial
                    if ($value['type'] == 'serial') {
                        // change value to serial
                        $val = $serial;
                    }

                    // if type is formula
                    if ($value['type'] == 'formula') {
                        // Ubah tipe
                        $style = $this->getStyle(align: $value['align'], useBold: false, formatColumn: $this->getFormatColumn($value['return_type']));
                    }

                    // push to cells
                    $cells[] = $this->printToCell(val: $val, type: $value['type'], style: $style); // create cell

                    // next col
                    $lastCol++;
                }

                // add new row
                $writer->addRow((new Row($cells)));

                // clear cells
                $cells = [];

                // add row index
                $lastRow++;

                // Increment serial
                $serial++;
            }

            // Buat summary penjumlahan
            if ($sumFooter) {
                // clear cells
                $cells = [];

                // row index for sum values
                $sumLastRow = $lastRow - ($useNumberFirstRow ? 0 : 1);

                // reset col index
                $lastCol = $startCol;

                // looping to sum footer
                foreach ($columns as $key => $value) {
                    // create footer style
                    $footerStyle = $this->getStyle(align: $value['align'], useBold: $sumFooterOptions['mergeColumn']['bold'], formatColumn: $this->getFormatColumn($value['type'] != 'formula' ? $value['type'] : $value['return_type']));

                    // if lastCol = startCol and ['mergeColumn']['count'] > 0
                    if ($lastCol == $startCol && $sumFooterOptions['mergeColumn']['count'] > 0) {
                        // create style
                        $mergeStyle = $this->getStyle(align: 'center', useBold: true);

                        // push to cells
                        $cells[] = Cell::fromValue($sumFooterOptions['mergeColumn']['text'], $mergeStyle);

                        // add col index
                        $lastCol++;

                        // continue
                        continue;
                    }

                    // if lastCol <= ['mergeColumn']['count'] that need to merge
                    if ($lastCol <= $sumFooterOptions['mergeColumn']['count']) {
                        // push to cells
                        $cells[] = Cell::fromValue("", $footerStyle);

                        // add col index
                        $lastCol++;

                        // continue
                        continue;
                    }

                    // if column is not include in sumColumns then just pass it
                    if (!in_array($key, $sumFooterOptions['sumColumns'])) {
                        // push to cells
                        $cells[] = Cell::fromValue("", $footerStyle);

                        // add col index
                        $lastCol++;

                        // continue
                        continue;
                    }

                    // get alphabet from index col
                    $colString = $this->num2alpha($lastCol);

                    // create sum formula
                    $sumRange = $colString . $sumStartRow . ":" . $colString . $sumLastRow;

                    // push to cell
                    $cells[] = Cell::fromValue("=SUM({$sumRange})", $footerStyle);

                    // add col index
                    $lastCol++;
                }

                // add new row
                $writer->addRow((new Row($cells)));

                // merge cell total
                $sumLastRow++;

                // merge cell
                $this->options->mergeCells(0, $sumLastRow, ($sumFooterOptions['mergeColumn']['count'] - 1), $sumLastRow, $writer->getCurrentSheet()->getIndex());
            }
        } else {
            // add last row with condition
            $lastRow = $useNumberFirstRow ? $lastRow + 1 : $lastRow;

            // clear cells
            $cells = [];

            // set for index
            $i = 0;

            // loop columns
            foreach ($columns as $key => $value) {
                // if index = 0 fill and push
                if ($i === 0) {
                    $cells[] = Cell::fromValue("Data not found");
                } else {
                    $cells[] = Cell::fromValue("");
                }
                $i++;
            }

            // add new row
            $writer->addRow((new Row($cells, $this->getStyle('center'))));

            // merge cell
            $this->options->mergeCells(0, $lastRow, (count($columns) - 1), $lastRow, $writer->getCurrentSheet()->getIndex());
        }
    }

    public function generateXlsx(string $filePath, array $records, array $columns, ?array $titles = null, ?bool $useNumberFirstRow = false, ?array $sumFooterOptions = [], ?string $sheetName = "DATA")
    {
        // create writer
        $writer = $this->generateNewSpreadsheet(filePath: $filePath, sheetName: $sheetName);

        // fill data
        $this->fillDataSheet(writer: $writer, records: $records, columns: $columns, titles: $titles, useNumberFirstRow: $useNumberFirstRow, sumFooter: !empty($sumFooterOptions), sumFooterOptions: $sumFooterOptions);

        // close writer
        $this->closeWriter($writer);
    }
}
