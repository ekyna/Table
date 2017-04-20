<?php

declare(strict_types=1);

namespace Ekyna\Component\Table\Filter;

use Ekyna\Component\Table\Context\ActiveFilter;
use Ekyna\Component\Table\Exception\LogicException;
use Ekyna\Component\Table\Exception\UnexpectedTypeException;
use Ekyna\Component\Table\Extension\FilterTypeExtensionInterface;
use Ekyna\Component\Table\Filter\Form\FilterType;
use Ekyna\Component\Table\Source\AdapterInterface;
use Ekyna\Component\Table\View;
use Ekyna\Component\Table\View\ActiveFilterView;
use Ekyna\Component\Table\View\AvailableFilterView;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use function get_class;

/**
 * Class ResolvedFilterType
 * @package Ekyna\Component\Table\Filter
 * @author  Etienne Dauvergne <contact@ekyna.com>
 *
 * @property FilterTypeExtensionInterface[] $typeExtensions
 */
final class ResolvedFilterType implements ResolvedFilterTypeInterface
{
    private FilterTypeInterface          $innerType;
    private array                        $typeExtensions;
    private ?ResolvedFilterTypeInterface $parent;
    private ?OptionsResolver             $optionsResolver = null;


    /**
     * Constructor.
     *
     * @param FilterTypeInterface              $innerType
     * @param array                            $typeExtensions
     * @param ResolvedFilterTypeInterface|null $parent
     */
    public function __construct(
        FilterTypeInterface $innerType,
        array $typeExtensions = [],
        ResolvedFilterTypeInterface $parent = null
    ) {
        foreach ($typeExtensions as $extension) {
            if (!$extension instanceof FilterTypeExtensionInterface) {
                throw new UnexpectedTypeException($extension, FilterTypeExtensionInterface::class);
            }
        }

        $this->innerType = $innerType;
        $this->typeExtensions = $typeExtensions;
        $this->parent = $parent;
    }

    /**
     * @inheritDoc
     */
    public function getBlockPrefix(): string
    {
        return $this->innerType->getBlockPrefix();
    }

    /**
     * @inheritDoc
     */
    public function getParent(): ?ResolvedFilterTypeInterface
    {
        return $this->parent;
    }

    /**
     * @inheritDoc
     */
    public function getInnerType(): FilterTypeInterface
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
    public function createBuilder(
        FormFactoryInterface $factory,
        string $name,
        array $options = []
    ): FilterBuilderInterface {
        $options = $this->getOptionsResolver()->resolve($options);

        $builder = new FilterBuilder($name, $factory, $options);
        $builder->setType($this);

        return $builder;
    }

    /**
     * @inheritDoc
     */
    public function buildFilter(FilterBuilderInterface $builder, array $options): void
    {
        if (null !== $this->parent) {
            $this->parent->buildFilter($builder, $options);
        }

        $this->innerType->buildFilter($builder, $options);

        foreach ($this->typeExtensions as $extension) {
            $extension->buildFilter($builder, $options);
        }
    }

    /**
     * @inheritDoc
     */
    public function createAvailableView(FilterInterface $filter, View\TableView $table): View\AvailableFilterView
    {
        return new View\AvailableFilterView($table);
    }

    /**
     * @inheritDoc
     */
    public function buildAvailableView(AvailableFilterView $view, FilterInterface $filter, array $options): void
    {
        if (null !== $this->parent) {
            $this->parent->buildAvailableView($view, $filter, $options);
        }

        $this->innerType->buildAvailableView($view, $filter, $options);

        foreach ($this->typeExtensions as $extension) {
            $extension->buildAvailableFilterView($view, $filter, $options);
        }
    }

    /**
     * @inheritDoc
     */
    public function createActiveView(FilterInterface $filter, View\TableView $table): View\ActiveFilterView
    {
        return new View\ActiveFilterView($table);
    }

    /**
     * @inheritDoc
     */
    public function buildActiveView(
        ActiveFilterView $view,
        FilterInterface $filter,
        ActiveFilter $activeFilter,
        array $options
    ): void {
        if (null !== $this->parent) {
            $this->parent->buildActiveView($view, $filter, $activeFilter, $options);
        }

        $this->innerType->buildActiveView($view, $filter, $activeFilter, $options);

        foreach ($this->typeExtensions as $extension) {
            $extension->buildActiveFilterView($view, $filter, $activeFilter, $options);
        }
    }

    /**
     * @inheritDoc
     */
    public function createForm(FilterInterface $filter, array $options): FormInterface
    {
        $builder = $filter
            ->getConfig()
            ->getFormFactory()
            ->createNamedBuilder($filter->getTable()->getName() . '_filter', FilterType::class);

        foreach ($this->typeExtensions as $extension) {
            if ($extension->buildFilterForm($builder, $filter, $options)) {
                return $builder->getForm();
            }
        }

        if ($this->innerType->buildForm($builder, $filter, $options)) {
            return $builder->getForm();
        }

        if (null !== $this->parent) {
            return $this->parent->createForm($filter, $options);
        }

        throw new LogicException(
            "None of the extensions, type or parent type were able to create the filter form.\n" .
            "Did you forget to return 'true' in some 'buildForm' methods of your filter types or extensions ?"
        );
    }

    /**
     * @inheritDoc
     */
    public function applyFilter(
        AdapterInterface $adapter,
        FilterInterface $filter,
        ActiveFilter $activeFilter,
        array $options
    ): bool {
        foreach ($this->typeExtensions as $extension) {
            if ($extension->applyFilter($adapter, $filter, $activeFilter, $options)) {
                return true;
            }
        }

        if ($this->innerType->applyFilter($adapter, $filter, $activeFilter, $options)) {
            return true;
        }

        if (null !== $this->parent) {
            return $this->parent->applyFilter($adapter, $filter, $activeFilter, $options);
        }

        throw new LogicException(
            "None of the extensions, type or parent type were able to apply the filter to the adapter.\n" .
            "Did you forget to return true in some 'applyFilter' methods ? Or maybe you need to create " .
            'a filter type extension that supports the ' . get_class($adapter) . ' adapter.'
        );
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
