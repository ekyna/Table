<?php

namespace Ekyna\Component\Table\Bridge\Doctrine\ORM\Extension\Column;

use Ekyna\Component\Table\Bridge\Doctrine\ORM\Source\EntityAdapter;
use Ekyna\Component\Table\Column\ColumnInterface;
use Ekyna\Component\Table\Context\ActiveSort;
use Ekyna\Component\Table\Extension\AbstractColumnTypeExtension;
use Ekyna\Component\Table\Extension\Core\Type\Column\PropertyType;
use Ekyna\Component\Table\Source\AdapterInterface;

/**
 * Class PropertyTypeExtension
 * @package Ekyna\Component\Table\Bridge\Doctrine\ORM\Extension\Column
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class PropertyTypeExtension extends AbstractColumnTypeExtension
{
    /**
     * @inheritDoc
     */
    public function applySort(AdapterInterface $adapter, ColumnInterface $column, ActiveSort $activeSort, array $options)
    {
        if (!$adapter instanceof EntityAdapter) {
            return false;
        }

        $property = $adapter->getQueryBuilderPath($column->getConfig()->getPropertyPath());

        $adapter
            ->getQueryBuilder()
            ->addOrderBy($property, $activeSort->getDirection());

        return true;
    }

    /**
     * @inheritDoc
     */
    public function getExtendedType()
    {
        return PropertyType::class;
    }
}
