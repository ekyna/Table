<?php

namespace Ekyna\Component\Table\Extension\Core\Type\Filter;

use Ekyna\Component\Table\AbstractFilterType;
use Ekyna\Component\Table\Util\FilterOperator;
use Symfony\Component\Form\FormBuilder;

/**
 * TextType
 */
class NumberType extends AbstractFilterType
{
    public function buildFilterFrom(FormBuilder $form, array $options)
    {
        $form
            ->add('operator', 'choice', array(
                'label' => false,
                'choices' => FilterOperator::getChoices($this->getOperators())
            ))
            ->add('value', 'number', array(
                'label' => false
            ))
        ;
    }

    public function getOperators()
    {
        return array(
            FilterOperator::EQUAL,
            FilterOperator::LOWER_THAN,
            FilterOperator::LOWER_THAN_OR_EQUAL,
            FilterOperator::GREATER_THAN,
            FilterOperator::GREATER_THAN_OR_EQUAL,
        );
    }

    public function getName()
    {
    	return 'number';
    }
}