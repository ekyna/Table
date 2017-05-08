<?php

namespace Ekyna\Component\Table\Bridge\Symfony\HttpFoundation;

use Ekyna\Component\Table\Extension\AbstractTableTypeExtension;
use Ekyna\Component\Table\Extension\Core\Type\TableType;
use Ekyna\Component\Table\TableBuilderInterface;

/**
 * Class TableTypeExtension
 * @package Ekyna\Component\Table\Bridge\Symfony\HttpFoundation
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class TableTypeExtension extends AbstractTableTypeExtension
{
    /**
     * @inheritDoc
     */
    public function buildTable(TableBuilderInterface $builder, array $options)
    {
        $builder->setHttpAdapter(new HttpAdapter());
    }

    /**
     * @inheritDoc
     */
    public function getExtendedType()
    {
        return TableType::class;
    }
}
