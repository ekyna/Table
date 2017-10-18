<?php

namespace Ekyna\Component\Table\Bridge\Symfony\Export;

use Ekyna\Component\Table\Export\AdapterInterface;
use Ekyna\Component\Table\TableInterface;
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

        $objects = [];
        foreach ($rows as $row) {
            $objects[] = $row->getData();
        }

        $columns = [];
        $visibleColumns = $table->getContext()->getVisibleColumns();
        foreach ($table->getColumns() as $column) {
            // If column is visible
            if (in_array($column->getName(), $visibleColumns, true)) {
                if (!empty($propertyPath = $column->getConfig()->getPropertyPath())) {
                    $columns[$column->getName()] = $propertyPath;
                }
            }
        }

        return $this->serializer->serialize($objects, $format, [
            'groups'  => ['TableExport'],
            'columns' => $columns,
        ]);
    }

    /**
     * @inheritDoc
     */
    public function getMimeType($format)
    {
        return 'application/' . $format;
    }

    /**
     * @inheritDoc
     */
    public function supports($format)
    {
        return in_array($format, ['json', 'xml'], true);
    }
}
