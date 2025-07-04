<?php

declare(strict_types=1);

namespace Ekyna\Component\Table\Extension\Core\Export;

use Ekyna\Component\Table\Export\AdapterInterface;
use Ekyna\Component\Table\TableInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

use function fclose;
use function fopen;
use function rewind;
use function stream_get_contents;
use function strtolower;

/**
 * Class XlsAdapter
 * @package Ekyna\Component\Table\Extension\Core\Export
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class XlsAdapter implements AdapterInterface
{
    public function export(TableInterface $table, string $format): ?string
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $rowIdx = 1;
        $colIdx = 1;

        // Headers
        foreach ($table->getColumns() as $column) {
            if (!$column->isExportable()) {
                continue;
            }

            $sheet->getCell([$colIdx, $rowIdx])->setValue($column->getLabel());

            $colIdx++;
        }
        $rowIdx++;

        // Cells
        $rows = $table->getSourceAdapter()->getSelection($table->getContext());

        foreach ($rows as $row) {
            $colIdx = 1;
            foreach ($table->getColumns() as $column) {
                if (!$column->isExportable()) {
                    continue;
                }

                $sheet->getCell([$colIdx, $rowIdx])->setValue($column->export($row));

                $colIdx++;
            }

            $rowIdx++;
        }

        $handle = fopen('php://temp,', 'r+');

        $writer = new Xls($spreadsheet);
        $writer->save($handle);

        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        return $content;
    }

    public function getMimeType(string $format): string
    {
        return 'application/vnd.ms-excel';
    }

    public function supports(string $format): bool
    {
        return strtolower($format) === 'xls';
    }
}

