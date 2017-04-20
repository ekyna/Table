<?php

declare(strict_types=1);

namespace Ekyna\Component\Table;

use Ekyna\Component\Table\Extension\Core\Type\TableType;
use Ekyna\Component\Table\Source\RowInterface;
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
     * @inheritDoc
     */
    public function buildTable(TableBuilderInterface $builder, array $options): void
    {
    }

    /**
     * @inheritDoc
     */
    public function buildView(TableView $view, TableInterface $table, array $options): void
    {
    }

    /**
     * @inheritDoc
     */
    public function buildRowView(View\RowView $view, RowInterface $row, array $options): void
    {
    }

    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
    }

    /**
     * @inheritDoc
     */
    public function getParent(): ?string
    {
        return TableType::class;
    }
}
