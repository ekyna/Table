<?php

declare(strict_types=1);

namespace Ekyna\Component\Table\Extension\Core\Type\Filter;

use Ekyna\Component\Table\Context\ActiveFilter;
use Ekyna\Component\Table\Filter\AbstractFilterType;
use Ekyna\Component\Table\Filter\FilterInterface;
use Ekyna\Component\Table\Util\FilterOperator;
use Ekyna\Component\Table\View\ActiveFilterView;
use Symfony\Component\Form\Extension\Core\Type as Form;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

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
    public function buildForm(FormBuilderInterface $builder, FilterInterface $filter, array $options): bool
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
                'label'       => false,
                'multiple'    => true,
                'required'    => true,
                'choices'     => $options['choices'],
                'constraints' => [
                    new NotBlank(),
                ],
            ]);

        return true;
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
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setRequired('choices')
            ->setDefault('choices_as_values', true)
            ->setAllowedTypes('choices', 'array')
            ->setAllowedTypes('choices_as_values', 'bool');
    }

    /**
     * @inheritDoc
     */
    public function getParent(): ?string
    {
        return FilterType::class;
    }
}
