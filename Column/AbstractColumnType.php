<?php

namespace Ekyna\Component\Table\Column;

use Ekyna\Component\Table\Context\ActiveSort;
use Ekyna\Component\Table\Extension\Core\Type\Column\ColumnType;
use Ekyna\Component\Table\Source\AdapterInterface;
use Ekyna\Component\Table\Source\RowInterface;
use Ekyna\Component\Table\View\CellView;
use Ekyna\Component\Table\View\HeadView;
use Symfony\Component\Form\Util\StringUtil;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AbstractColumnType
 * @package Ekyna\Component\Table
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
abstract class AbstractColumnType implements ColumnTypeInterface
{
    /**
     * @inheritdoc
     */
    public function buildColumn(ColumnBuilderInterface $builder, array $options)
    {
    }

    /**
     * @inheritdoc
     */
    public function buildHeadView(HeadView $view, ColumnInterface $column, array $options)
    {
    }

    /**
     * @inheritdoc
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
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
    }

    /**
     * @inheritDoc
     */
    public function getBlockPrefix()
    {
        return StringUtil::fqcnToBlockPrefix(get_class($this));
    }

    /**
     * @inheritDoc
     */
    public function getParent()
    {
        return ColumnType::class;
    }
}
