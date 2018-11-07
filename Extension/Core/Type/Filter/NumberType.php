<?php

namespace Ekyna\Component\Table\Extension\Core\Type\Filter;

use Ekyna\Component\Table\Filter\AbstractFilterType;
use Ekyna\Component\Table\Filter\FilterInterface;
use Ekyna\Component\Table\Util\FilterOperator;
use Symfony\Component\Form\Extension\Core\Type as FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class NumberType
 * @package Ekyna\Component\Table\Extension\Core\Type\Filter
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class NumberType extends AbstractFilterType
{
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
                    FilterOperator::LOWER_THAN,
                    FilterOperator::LOWER_THAN_OR_EQUAL,
                    FilterOperator::GREATER_THAN,
                    FilterOperator::GREATER_THAN_OR_EQUAL,
                ]),
            ])
            ->add('value', FormType\NumberType::class, [
                'label'    => false,
                'required' => true,
                'constraints' => [
                    new NotBlank()
                ],
            ]);

        return true;
    }

    /**
     * @inheritdoc
     */
    public function getParent()
    {
        return FilterType::class;
    }
}
