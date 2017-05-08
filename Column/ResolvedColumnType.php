<?php

namespace Ekyna\Component\Table\Column;

use Ekyna\Component\Table\Context\ActiveSort;
use Ekyna\Component\Table\Exception\LogicException;
use Ekyna\Component\Table\Exception\UnexpectedTypeException;
use Ekyna\Component\Table\Extension\ColumnTypeExtensionInterface;
use Ekyna\Component\Table\Source\AdapterInterface;
use Ekyna\Component\Table\Source\RowInterface;
use Ekyna\Component\Table\View;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ResolvedColumnType
 * @package Ekyna\Component\Table\Column
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class ResolvedColumnType implements ResolvedColumnTypeInterface
{
    /**
     * @var ColumnTypeInterface
     */
    private $innerType;

    /**
     * @var ColumnTypeExtensionInterface[]
     */
    private $typeExtensions;

    /**
     * @var ResolvedColumnTypeInterface|null
     */
    private $parent;

    /**
     * @var OptionsResolver
     */
    private $optionsResolver;


    /**
     * Constructor.
     *
     * @param ColumnTypeInterface              $innerType
     * @param array                            $typeExtensions
     * @param ResolvedColumnTypeInterface|null $parent
     */
    public function __construct(
        ColumnTypeInterface $innerType,
        array $typeExtensions = array(),
        ResolvedColumnTypeInterface $parent = null
    ) {
        foreach ($typeExtensions as $extension) {
            if (!$extension instanceof ColumnTypeExtensionInterface) {
                throw new UnexpectedTypeException($extension, ColumnTypeExtensionInterface::class);
            }
        }

        $this->innerType = $innerType;
        $this->typeExtensions = $typeExtensions;
        $this->parent = $parent;
    }

    /**
     * @inheritDoc
     */
    public function getBlockPrefix()
    {
        return $this->innerType->getBlockPrefix();
    }

    /**
     * @inheritDoc
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @inheritDoc
     */
    public function getInnerType()
    {
        return $this->innerType;
    }

    /**
     * @inheritDoc
     */
    public function getTypeExtensions()
    {
        return $this->typeExtensions;
    }

    /**
     * {@inheritdoc}
     */
    public function createBuilder($name, array $options = array())
    {
        $options = $this->getOptionsResolver()->resolve($options);

        $builder = new ColumnBuilder($name, $options);
        $builder->setType($this);

        return $builder;
    }

    /**
     * @inheritDoc
     */
    public function buildColumn(ColumnBuilderInterface $builder, array $options)
    {
        if (null !== $this->parent) {
            $this->parent->buildColumn($builder, $options);
        }

        $this->innerType->buildColumn($builder, $options);

        foreach ($this->typeExtensions as $extension) {
            $extension->buildColumn($builder, $options);
        }
    }

    /**
     * @inheritDoc
     */
    public function createHeadView(ColumnInterface $column, View\TableView $table)
    {
        return new View\HeadView($table);
    }

    /**
     * @inheritDoc
     */
    public function buildHeadView(View\HeadView $view, ColumnInterface $column, array $options)
    {
        if (null !== $this->parent) {
            $this->parent->buildHeadView($view, $column, $options);
        }

        $this->innerType->buildHeadView($view, $column, $options);

        foreach ($this->typeExtensions as $extension) {
            $extension->buildHeadView($view, $column, $options);
        }
    }

    /**
     * @inheritDoc
     */
    public function createCellView(ColumnInterface $column, View\RowView $row)
    {
        return new View\CellView($row);
    }

    /**
     * @inheritDoc
     */
    public function buildCellView(View\CellView $view, ColumnInterface $column, RowInterface $row, array $options)
    {
        if (null !== $this->parent) {
            $this->parent->buildCellView($view, $column, $row, $options);
        }

        $this->innerType->buildCellView($view, $column, $row, $options);

        foreach ($this->typeExtensions as $extension) {
            $extension->buildCellView($view, $column, $row, $options);
        }
    }

    /**
     * @inheritDoc
     */
    public function applySort(AdapterInterface $adapter, ColumnInterface $column, ActiveSort $activeSort, array $options)
    {
        foreach ($this->typeExtensions as $extension) {
            if ($extension->applyFilter($adapter, $column, $activeSort, $options)) {
                return true;
            }
        }

        if ($this->innerType->applySort($adapter, $column, $activeSort, $options)) {
            return true;
        }

        if (null !== $this->parent) {
            return $this->parent->applySort($adapter, $column, $activeSort, $options);
        }

        throw new LogicException(
            "None of the extensions, type or parent type were able to apply the sort to the adapter.\n" .
            "Did you forget to return true in some 'applySort' methods ? Or maybe you need to create " .
            "a filter type extension that supports the " . get_class($adapter) . " adapter."
        );
    }

    /**
     * @inheritDoc
     */
    public function getOptionsResolver()
    {
        if (null === $this->optionsResolver) {
            if (null !== $this->parent) {
                $this->optionsResolver = clone $this->parent->getOptionsResolver();
            } else {
                $this->optionsResolver = new OptionsResolver();
            }

            $this->innerType->configureOptions($this->optionsResolver);

            foreach ($this->typeExtensions as $extension) {
                $extension->configureOptions($this->optionsResolver);
            }
        }

        return $this->optionsResolver;
    }
}
