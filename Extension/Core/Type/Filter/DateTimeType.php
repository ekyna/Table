<?php

namespace Ekyna\Component\Table\Extension\Core\Type\Filter;

use Ekyna\Component\Table\Context\ActiveFilter;
use Ekyna\Component\Table\Filter\AbstractFilterType;
use Ekyna\Component\Table\Filter\FilterInterface;
use Ekyna\Component\Table\Util\FilterOperator;
use Symfony\Component\Form\Extension\Core\Type as FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Ekyna\Component\Table\View\ActiveFilterView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class DateTimeType
 * @package Ekyna\Component\Table\Extension\Core\Type\Filter
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class DateTimeType extends AbstractFilterType
{
    /**
     * @inheritDoc
     */
    public function buildActiveView(ActiveFilterView $view, FilterInterface $filter, ActiveFilter $activeFilter, array $options)
    {
        /** @var \DateTime $date */
        $date = $activeFilter->getValue();

        $view->vars['value'] = $date->format($options['time'] ? 'd/m/Y H:i' : 'd/m/Y'); // TODO localized format.
    }

    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, FilterInterface $filter, array $options)
    {
        $builder
            ->add('operator', FormType\ChoiceType::class, [
                'label'    => false,
                'required' => true,
                'choices'  => FilterOperator::getChoices([
                    FilterOperator::EQUAL,
                    FilterOperator::NOT_EQUAL,
                    FilterOperator::LOWER_THAN,
                    FilterOperator::LOWER_THAN_OR_EQUAL,
                    FilterOperator::GREATER_THAN,
                    FilterOperator::GREATER_THAN_OR_EQUAL,
                ]),
            ])
            ->add('value', $options['time'] ? FormType\DateTimeType::class : FormType\DateType::class, [
                'label'    => false,
                'required' => true,
                'input'    => 'datetime',
                'widget'   => 'single_text',
            ]);

        return true;
    }

    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('time', false)
            ->setAllowedTypes('time', 'bool');
    }

    /**
     * @inheritdoc
     */
    public function getParent()
    {
        return FilterType::class;
    }
}
