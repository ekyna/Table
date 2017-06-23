<?php

namespace Ekyna\Component\Table\Extension;

use Ekyna\Component\Table\Column\ColumnBuilderInterface;
use Ekyna\Component\Table\Column\ColumnInterface;
use Ekyna\Component\Table\Context\ActiveSort;
use Ekyna\Component\Table\Source\AdapterInterface;
use Ekyna\Component\Table\Source\RowInterface;
use Ekyna\Component\Table\View\CellView;
use Ekyna\Component\Table\View\HeadView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AbstractColumnTypeExtension
 * @package Ekyna\Component\Table\Extension
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
abstract class AbstractColumnTypeExtension implements ColumnTypeExtensionInterface
{
    /**
     * @inheritDoc
     */
    public function buildColumn(ColumnBuilderInterface $builder, array $options)
    {
    }

    /**
     * @inheritDoc
     */
    public function buildHeadView(HeadView $view, ColumnInterface $column, array $options)
    {
    }

    /**
     * @inheritDoc
     */
    public function buildCellView(CellView $view, ColumnInterface $column, RowInterface $row, array $options)
    {
    }

    /**
     * @inheritDoc
     */
    public function applySort(AdapterInterface $adapter, ColumnInterface $column, ActiveSort $activeSort, array $options)
    {

    }

    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
    }
}
