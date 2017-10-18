<?php

namespace Ekyna\Component\Table;

use Ekyna\Component\Table\Extension\Core\Type\TableType;
use Ekyna\Component\Table\View\TableView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AbstractTableType
 * @package Ekyna\Component\Table
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
abstract class AbstractTableType implements TableTypeInterface
{
    /**
     * @inheritdoc
     */
    public function buildTable(TableBuilderInterface $builder, array $options)
    {
    }

    /**
     * @inheritDoc
     */
    public function buildView(TableView $view, TableInterface $table, array $options)
    {
    }

    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return TableType::class;
    }
}
