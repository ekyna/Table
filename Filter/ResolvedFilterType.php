<?php

namespace Ekyna\Component\Table\Filter;

use Ekyna\Component\Table\Context\ActiveFilter;
use Ekyna\Component\Table\Exception\LogicException;
use Ekyna\Component\Table\Exception\UnexpectedTypeException;
use Ekyna\Component\Table\Extension\FilterTypeExtensionInterface;
use Ekyna\Component\Table\Http\ParametersHelper;
use Ekyna\Component\Table\Source\AdapterInterface;
use Ekyna\Component\Table\View;
use Ekyna\Component\Table\View\ActiveFilterView;
use Ekyna\Component\Table\View\AvailableFilterView;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ResolvedFilterType
 * @package Ekyna\Component\Table\Filter
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class ResolvedFilterType implements ResolvedFilterTypeInterface
{
    /**
     * @var FilterTypeInterface
     */
    private $innerType;

    /**
     * @var FilterTypeExtensionInterface[]
     */
    private $typeExtensions;

    /**
     * @var ResolvedFilterTypeInterface|null
     */
    private $parent;

    /**
     * @var OptionsResolver
     */
    private $optionsResolver;


    /**
     * Constructor.
     *
     * @param FilterTypeInterface              $innerType
     * @param array                            $typeExtensions
     * @param ResolvedFilterTypeInterface|null $parent
     */
    public function __construct(
        FilterTypeInterface $innerType,
        array $typeExtensions = array(),
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
    public function createBuilder(FormFactoryInterface $factory, $name, array $options = array())
    {
        $options = $this->getOptionsResolver()->resolve($options);

        $builder = new FilterBuilder($name, $factory, $options);
        $builder->setType($this);

        return $builder;
    }

    /**
     * @inheritDoc
     */
    public function buildFilter(FilterBuilderInterface $builder, array $options)
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
    public function createAvailableView(FilterInterface $filter, View\TableView $table)
    {
        return new View\AvailableFilterView($table);
    }

    /**
     * @inheritDoc
     */
    public function buildAvailableView(AvailableFilterView $view, FilterInterface $filter, array $options)
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
    public function createActiveView(FilterInterface $filter, View\TableView $table)
    {
        return new View\ActiveFilterView($table);
    }

    /**
     * @inheritDoc
     */
    public function buildActiveView(ActiveFilterView $view, FilterInterface $filter, ActiveFilter $activeFilter, array $options)
    {
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
    public function createForm(FilterInterface $filter, array $options)
    {
        $builder = $filter
            ->getConfig()
            ->getFormFactory()
            ->createNamedBuilder($filter->getTable()->getName(), FormType::class, null, [
                'action'     => $filter->getTable()->getParametersHelper()->getAddFilterHref($filter),
                'method'     => 'GET',
                'data_class' => ActiveFilter::class,
            ])
            ->add(ParametersHelper::ADD_FILTER, HiddenType::class, [
                'property_path' => 'filterName'
            ]);

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
    public function applyFilter(AdapterInterface $adapter, FilterInterface $filter, ActiveFilter $activeFilter, array $options)
    {
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
