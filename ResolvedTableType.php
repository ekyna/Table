<?php

declare(strict_types=1);

namespace Ekyna\Component\Table;

use Ekyna\Component\Table\Source\RowInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ResolvedTableType
 * @package Ekyna\Component\Table
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class ResolvedTableType implements ResolvedTableTypeInterface
{
    private TableTypeInterface $innerType;
    /** @var Extension\TableTypeExtensionInterface[] */
    private array                       $typeExtensions;
    private ?ResolvedTableTypeInterface $parent;
    private ?OptionsResolver            $optionsResolver = null;


    /**
     * Constructor.
     *
     * @param TableTypeInterface              $innerType
     * @param array                           $typeExtensions
     * @param ResolvedTableTypeInterface|null $parent
     */
    public function __construct(
        TableTypeInterface $innerType,
        array $typeExtensions = [],
        ResolvedTableTypeInterface $parent = null
    ) {
        foreach ($typeExtensions as $extension) {
            if (!$extension instanceof Extension\TableTypeExtensionInterface) {
                throw new Exception\UnexpectedTypeException($extension, Extension\TableTypeExtensionInterface::class);
            }
        }

        $this->innerType = $innerType;
        $this->typeExtensions = $typeExtensions;
        $this->parent = $parent;
    }

    /**
     * @inheritDoc
     */
    public function getParent(): ResolvedTableTypeInterface
    {
        return $this->parent;
    }

    /**
     * @inheritDoc
     */
    public function getInnerType(): TableTypeInterface
    {
        return $this->innerType;
    }

    /**
     * @inheritDoc
     */
    public function getTypeExtensions(): array
    {
        return $this->typeExtensions;
    }

    /**
     * @inheritDoc
     */
    public function createBuilder(TableFactoryInterface $factory, string $name, array $options = []): TableBuilderInterface
    {
        $options = $this->getOptionsResolver()->resolve($options);

        $builder = new TableBuilder($name, $factory, $options);
        $builder->setType($this);

        return $builder;
    }

    /**
     * @inheritDoc
     */
    public function createView(TableInterface $table): View\TableView
    {
        return new View\TableView();
    }

    /**
     * @inheritDoc
     */
    public function buildTable(TableBuilderInterface $builder, array $options): void
    {
        if (null !== $this->parent) {
            $this->parent->buildTable($builder, $options);
        }

        $this->innerType->buildTable($builder, $options);

        foreach ($this->typeExtensions as $extension) {
            $extension->buildTable($builder, $options);
        }
    }

    /**
     * @inheritDoc
     */
    public function buildView(View\TableView $view, TableInterface $table, array $options): void
    {
        if (null !== $this->parent) {
            $this->parent->buildView($view, $table, $options);
        }

        $this->innerType->buildView($view, $table, $options);

        foreach ($this->typeExtensions as $extension) {
            $extension->buildView($view, $table, $options);
        }
    }

    /**
     * @inheritDoc
     */
    public function buildRowView(View\RowView $view, RowInterface $row, array $options): void
    {
        if (null !== $this->parent) {
            $this->parent->buildRowView($view, $row, $options);
        }

        $this->innerType->buildRowView($view, $row, $options);

        foreach ($this->typeExtensions as $extension) {
            $extension->buildRowView($view, $row, $options);
        }
    }

    /**
     * @inheritDoc
     */
    public function getOptionsResolver(): OptionsResolver
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
