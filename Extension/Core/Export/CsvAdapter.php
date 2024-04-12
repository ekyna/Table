<?php

declare(strict_types=1);

namespace Ekyna\Component\Table\Extension\Core\Export;

use Ekyna\Component\Table\Export\AdapterInterface;
use Ekyna\Component\Table\TableInterface;

use function array_replace;
use function fclose;
use function fopen;
use function fputcsv;
use function rewind;
use function stream_get_contents;

/**
 * Class CsvAdapter
 * @package Ekyna\Component\Table\Extension\Core\Export
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class CsvAdapter implements AdapterInterface
{
    private array $options;

    public function __construct(array $options = [])
    {
        $this->options = array_replace([
            'delimiter'   => ',',
            'enclosure'   => '"',
            'escape_char' => '\\',
        ], $options);
    }

    public function export(TableInterface $table, string $format): ?string
    {
        $headers = [];
        foreach ($table->getColumns() as $column) {
            if (!$column->isExportable()) {
                continue;
            }

            $headers[] = $column->getLabel();
        }

        $data = [$headers];

        $rows = $table->getSourceAdapter()->getSelection($table->getContext());

        foreach ($rows as $row) {
            $datum = [];

            foreach ($table->getColumns() as $column) {
                if (!$column->isExportable()) {
                    continue;
                }

                $datum[] = $column->export($row);
            }

            $data[] = $datum;
        }

        if (empty($data)) {
            return null;
        }

        $handle = fopen('php://temp,', 'w+');

        foreach ($data as $datum) {
            fputcsv(
                $handle,
                $datum,
                $this->options['delimiter'],
                $this->options['enclosure'],
                $this->options['escape_char']
            );
        }

        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        return $content;
    }

    public function getMimeType(string $format): string
    {
        return 'text/csv';
    }

    public function supports(string $format): bool
    {
        return strtolower($format) === 'csv';
    }
}
