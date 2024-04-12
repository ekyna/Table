<?php

declare(strict_types=1);

namespace Ekyna\Component\Table\Extension\Core\Type\Extension;

use Ekyna\Component\Table\Extension\AbstractTableTypeExtension;
use Ekyna\Component\Table\Extension\Core\Export\CsvAdapter;
use Ekyna\Component\Table\Extension\Core\Type\TableType;
use Ekyna\Component\Table\TableBuilderInterface;

/**
 * Class ExportTypeExtension
 * @package Ekyna\Component\Table\Extension\Core\Type\Extension
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class ExportTypeExtension extends AbstractTableTypeExtension
{
    public function buildTable(TableBuilderInterface $builder, array $options): void
    {
        $builder->addExportAdapter(new CsvAdapter());
    }

    public static function getExtendedTypes(): array
    {
        return [TableType::class];
    }
}
