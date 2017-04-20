<?php

declare(strict_types=1);

namespace Ekyna\Component\Table\Extension\Core\Type\Column;

use Ekyna\Component\Table\Column\AbstractColumnType;
use Ekyna\Component\Table\Column\ColumnInterface;
use Ekyna\Component\Table\Context\ActiveSort;
use Ekyna\Component\Table\Extension\Core\Source\ArrayAdapter;
use Ekyna\Component\Table\Source\AdapterInterface;
use Ekyna\Component\Table\Source\RowInterface;
use Ekyna\Component\Table\View\CellView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class PropertyType
 * @package Ekyna\Component\Table\Extension\Core\Type\Column
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class PropertyType extends AbstractColumnType
{
    /**
     * @inheritDoc
     */
    public function buildCellView(CellView $view, ColumnInterface $column, RowInterface $row, array $options): void
    {
        $view->vars = array_replace($view->vars, [
            'value' => $row->getData($column->getConfig()->getPropertyPath()),
        ]);
    }

    /**
     * @inheritDoc
     */
    public function applySort(
        AdapterInterface $adapter,
        ColumnInterface $column,
        ActiveSort $activeSort,
        array $options
    ): bool {
        if (!$adapter instanceof ArrayAdapter) {
            return false;
        }

        $closure = $adapter->buildSortClosure(
            $column->getConfig()->getPropertyPath(),
            $activeSort->getDirection()
        );

        $adapter->addSortClosure($closure);

        return true;
    }

    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'sortable' => true,
        ]);
    }

    /**
     * @inheritDoc
     */
    public function getParent(): ?string
    {
        return ColumnType::class;
    }
}
