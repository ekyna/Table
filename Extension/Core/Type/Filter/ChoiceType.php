<?php

namespace Ekyna\Component\Table\Extension\Core\Type\Filter;

use Ekyna\Component\Table\Context\ActiveFilter;
use Ekyna\Component\Table\Filter\AbstractFilterType;
use Ekyna\Component\Table\Filter\FilterInterface;
use Ekyna\Component\Table\Util\FilterOperator;
use Ekyna\Component\Table\View\ActiveFilterView;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type as Form;

/**
 * Class ChoiceType
 * @package Ekyna\Component\Table\Extension\Core\Type\Filter
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class ChoiceType extends AbstractFilterType
{
    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, FilterInterface $filter, array $options)
    {
        $builder
            ->add('operator', Form\ChoiceType::class, [
                'label'    => false,
                'required' => true,
                'choices'  => FilterOperator::getChoices([
                    FilterOperator::IN,
                    FilterOperator::NOT_IN,
                ]),
            ])
            ->add('value', Form\ChoiceType::class, [
                'label'    => false,
                'multiple' => true,
                'required' => true,
                'choices'  => $options['choices'],
            ]);

        return true;
    }

    /**
     * @inheritDoc
     */
    public function buildActiveView(ActiveFilterView $view, FilterInterface $filter, ActiveFilter $activeFilter, array $options)
    {
        $choices = $options['choices'];

        if ($options['choices_as_values']) {
            $transform = function ($value) use ($choices) {
                if (false !== $choice = array_search($value, $choices)) {
                    return $choice;
                }

                return $value;
            };
        } else {
            $transform = function ($value) use ($choices) {
                return isset($choices[$value]) ? $choices[$value] : $value;
            };
        }

        $value = $activeFilter->getValue();
        if (is_array($value)) {
            $value = array_map($transform, $value);
        } else {
            $value = $transform($value);
        }

        $view->vars['value'] = $value;
    }

    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired('choices')
            ->setDefault('choices_as_values', true)
            ->setAllowedTypes('choices', 'array')
            ->setAllowedTypes('choices_as_values', 'bool');
    }

    /**
     * @inheritdoc
     */
    public function getParent()
    {
        return FilterType::class;
    }
}
