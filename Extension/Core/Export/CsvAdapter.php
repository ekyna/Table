<?php

namespace Ekyna\Component\Table\Extension\Core\Export;

use Ekyna\Component\Table\Export\AbstractAdapter;
use Ekyna\Component\Table\TableInterface;

/**
 * Class CsvAdapter
 * @package Ekyna\Component\Table\Extension\Core\Export
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class CsvAdapter extends AbstractAdapter
{
    /**
     * @inheritDoc
     */
    public function export(TableInterface $table, $format)
    {
        $data = $this->getSelectedData($table);

        $content = '';
        foreach ($data as $datum) {
            /** @noinspection PhpParamsInspection */
            $content .= implode(';', $datum) . "\n";
        }

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
