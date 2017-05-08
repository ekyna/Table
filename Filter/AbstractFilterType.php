<?php

namespace Ekyna\Component\Table\Filter;

use Ekyna\Component\Table\Context\ActiveFilter;
use Ekyna\Component\Table\Extension\Core\Type\Filter\FilterType;
use Ekyna\Component\Table\Source\AdapterInterface;
use Ekyna\Component\Table\View\ActiveFilterView;
use Ekyna\Component\Table\View\AvailableFilterView;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Util\StringUtil;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AbstractFilterType
 * @package Ekyna\Component\Table
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
abstract class AbstractFilterType implements FilterTypeInterface
{
    /**
     * @inheritdoc
     */
    public function buildFilter(FilterBuilderInterface $builder, array $options)
    {
    }

    /**
     * @inheritdoc
     */
    public function buildAvailableView(AvailableFilterView $view, FilterInterface $filter, array $options)
    {
    }

    /**
     * @inheritdoc
     */
    public function buildActiveView(ActiveFilterView $view, FilterInterface $filter, ActiveFilter $activeFilter, array $options)
    {
    }

    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, FilterInterface $filter, array $options)
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public function applyFilter(AdapterInterface $adapter, FilterInterface $filter, ActiveFilter $activeFilter, array $options)
    {
        return false;
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
        return FilterType::class;
    }
}
