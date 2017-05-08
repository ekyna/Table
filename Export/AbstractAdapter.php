<?php

namespace Ekyna\Component\Table\Export;

use Ekyna\Component\Table\TableInterface;

/**
 * Class AbstractAdapter
 * @package Ekyna\Component\Table\Export
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
abstract class AbstractAdapter implements AdapterInterface
{
    /**
     * Return the selected rows.
     *
     * @param TableInterface $table
     *
     * @return \Ekyna\Component\Table\Source\Row[]
     */
    protected function getSelectedData(TableInterface $table)
    {
        $rows = $table->getSourceAdapter()->getSelection($table->getContext());

        $data = [];

        $visibleColumns = $table->getContext()->getVisibleColumns();
        foreach ($rows as $row) {
            $datum = [];
            /** @var \Ekyna\Component\Table\Column\ColumnInterface $column */
            foreach ($table->getColumns() as $column) {
                // If column is visible
                if (in_array($column->getName(), $visibleColumns)) {
                    // TODO $value = $column->exportValue($row);
                    $value = $row->getData($column->getConfig()->getPropertyPath());
                    try {
                        $toString = (string)$value;
                    } catch (\Exception $e) {
                        $toString = null;
                    }
                    $datum[$column->getName()] = $toString;
                }
            }
            $data[] = $datum;
        }

        return $data;
    }
}
