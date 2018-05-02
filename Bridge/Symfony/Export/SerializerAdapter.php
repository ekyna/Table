<?php

namespace Ekyna\Component\Table\Bridge\Symfony\Export;

use Ekyna\Component\Table\Export\AdapterInterface;
use Ekyna\Component\Table\TableInterface;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class SerializerAdapter
 * @package Ekyna\Component\Table\Bridge\Symfony\Export
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class SerializerAdapter implements AdapterInterface
{
    /**
     * @var SerializerInterface
     */
    private $serializer;


    /**
     * Constructor.
     *
     * @param SerializerInterface $serializer
     */
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @inheritDoc
     */
    public function export(TableInterface $table, $format)
    {
        $rows = $table->getSourceAdapter()->getSelection($table->getContext());

        $columns = $headers = [];

        $visibleColumns = $table->getContext()->getVisibleColumns();
        foreach ($table->getColumns() as $column) {
            // If column is visible
            if (in_array($column->getName(), $visibleColumns, true)) {
                if (!empty($propertyPath = $column->getConfig()->getPropertyPath())) {
                    $columns[$column->getName()] = $propertyPath;
                    $headers[] = $column->getName();
                }
            }
        }

        $data = [];

        foreach ($rows as $row) {
            $datum = [];

            foreach ($columns as $name => $path) {
                $datum[$name] = $row->getData($path);
            }

            $data[] = $datum;
        }

        $context = [
            'groups' => ['TableExport'],
        ];

        if ($format === 'csv') {
            $context[CsvEncoder::DELIMITER_KEY] = ';';
            $context[CsvEncoder::HEADERS_KEY] = $headers;
        }

        return $this->serializer->serialize($data, $format, $context);
    }

    /**
     * @inheritDoc
     */
    public function getMimeType($format)
    {
        if ($format === 'csv') {
            return 'text/csv';
        }

        return 'application/' . $format;
    }

    /**
     * @inheritDoc
     */
    public function supports($format)
    {
        return in_array($format, ['json', 'xml', 'csv'], true);
    }
}
