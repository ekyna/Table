<?php

namespace Ekyna\Component\Table\Extension\Core\Type\Filter;

use Ekyna\Component\Table\AbstractFilterType;
use Ekyna\Component\Table\Util\FilterOperator;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class TextType
 * @package Ekyna\Component\Table\Extension\Core\Type\Filter
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class TextType extends AbstractFilterType
{
    public function buildFilterFrom(FormBuilderInterface $form, array $options)
    {
        $form
            ->add('operator', 'choice', array(
                'label' => false,
                'choices' => FilterOperator::getChoices($this->getOperators())
            ))
            ->add('value', 'text', array(
                'label' => false
            ))
        ;
    }

    public function getOperators()
    {
        return array(
            FilterOperator::LIKE,
            FilterOperator::NOT_LIKE,
            FilterOperator::START_WITH,
            FilterOperator::NOT_START_WITH,
            FilterOperator::END_WITH,
            FilterOperator::NOT_START_WITH
        );
    }

    public function getName()
    {
    	return 'text';
    }
}