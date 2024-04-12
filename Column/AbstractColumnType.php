<?php

declare(strict_types=1);

namespace Ekyna\Component\Table\Column;

use Ekyna\Component\Table\Context\ActiveSort;
use Ekyna\Component\Table\Extension\Core\Type\Column\ColumnType;
use Ekyna\Component\Table\Source\AdapterInterface;
use Ekyna\Component\Table\Source\RowInterface;
use Ekyna\Component\Table\View\CellView;
use Ekyna\Component\Table\View\HeadView;
use Symfony\Component\Form\Util\StringUtil;
use Symfony\Component\OptionsResolver\OptionsResolver;

use function get_class;

/**
 * Class AbstractColumnType
 * @package Ekyna\Component\Table
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
abstract class AbstractColumnType implements ColumnTypeInterface
{
    /**
     * @inheritDoc
     */
    public function buildColumn(ColumnBuilderInterface $builder, array $options): void
    {
    }

    /**
     * @inheritDoc
     */
    public function buildHeadView(HeadView $view, ColumnInterface $column, array $options): void
    {
    }

    /**
     * @inheritDoc
     */
    public function buildCellView(CellView $view, ColumnInterface $column, RowInterface $row, array $options): void
    {
    }

    /**
     * @inheritDoc
     */
    public function configureAdapter(
        AdapterInterface $adapter,
        ColumnInterface  $column,
        array            $options
    ): void {
    }

    /**
     * @inheritDoc
     */
    public function applySort(
        AdapterInterface $adapter,
        ColumnInterface  $column,
        ActiveSort       $activeSort,
        array            $options
    ): bool {
        return false;
    }

    /**
     * @inheritDoc
     */
    public function export(ColumnInterface $column, RowInterface $row, array $options): ?string
    {
        return null;
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
    public function getBlockPrefix(): string
    {
        return StringUtil::fqcnToBlockPrefix(get_class($this));
    }

    /**
     * @inheritDoc
     */
    public function getParent(): ?string
    {
        return ColumnType::class;
    }
}
