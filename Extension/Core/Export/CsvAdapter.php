<?php

declare(strict_types=1);

namespace Ekyna\Component\Table\Extension\Core\Export;

use Ekyna\Component\Table\Exception\InvalidArgumentException;
use Ekyna\Component\Table\Export\AdapterInterface;
use Ekyna\Component\Table\TableInterface;
use Exception;

use function array_keys;
use function array_replace;
use function fclose;
use function fopen;
use function fputcsv;
use function in_array;
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
        $rows = $table->getSourceAdapter()->getSelection($table->getContext());

        $data = [];

        $visibleColumns = $table->getContext()->getVisibleColumns();
        foreach ($rows as $row) {
            $datum = [];

            foreach ($table->getColumns() as $column) {
                if (in_array($column->getName(), $visibleColumns, true)) {
                    if (empty($propertyPath = $column->getConfig()->getPropertyPath())) {
                        continue;
                    }

                    $value = $row->getData($propertyPath);

                    try {
                        $toString = (string)$value;
                    } catch (Exception $e) {
                        $toString = null;
                    }

                    $datum[$column->getName()] = $toString;
                }
            }

            if (!empty($datum)) {
                $data[] = $datum;
            }
        }

        if (empty($data)) {
            return null;
        }

        $handle = fopen('php://temp,', 'w+');

        $headers = null;
        foreach ($data as $datum) {
            if (null === $headers) {
                $headers = array_keys($datum);
                fputcsv(
                    $handle,
                    $headers,
                    $this->options['delimiter'],
                    $this->options['enclosure'],
                    $this->options['escape_char']
                );
            } elseif (array_keys($datum) !== $headers) {
                throw new InvalidArgumentException('Unexpected data');
            }

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
