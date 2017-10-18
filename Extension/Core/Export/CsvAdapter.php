<?php

namespace Ekyna\Component\Table\Extension\Core\Export;

use Ekyna\Component\Table\Exception\InvalidArgumentException;
use Ekyna\Component\Table\Export\AdapterInterface;
use Ekyna\Component\Table\TableInterface;

/**
 * Class CsvAdapter
 * @package Ekyna\Component\Table\Extension\Core\Export
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class CsvAdapter implements AdapterInterface
{
    /**
     * @var array
     */
    private $options;


    /**
     * Constructor.
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->options = array_replace([
            'delimiter'   => ';',
            'enclosure'   => '"',
            'escape_char' => '\\',
        ], $options);
    }

    /**
     * @inheritDoc
     */
    public function export(TableInterface $table, $format)
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
                    } catch (\Exception $e) {
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
                fputcsv($handle, $headers, $this->options['delimiter'], $this->options['enclosure'], $this->options['escape_char']);
            } elseif (array_keys($datum) !== $headers) {
                throw new InvalidArgumentException('Unexpected data');
            }

            fputcsv($handle, $datum, $this->options['delimiter'], $this->options['enclosure'], $this->options['escape_char']);
        }

        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        return $content;
    }

    /**
     * @inheritDoc
     */
    public function getMimeType($format)
    {
        return 'text/csv';
    }

    /**
     * @inheritDoc
     */
    public function supports($format)
    {
        return strtolower($format) === 'csv';
    }
}
