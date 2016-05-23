<?php

namespace Ekyna\Component\Table\Extension\Core\Type\Filter;

use Ekyna\Component\Table\AbstractFilterType;
use Ekyna\Component\Table\Util\FilterOperator;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class TextType
 * @package Ekyna\Component\Table\Extension\Core\Type\Filter
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class TextType extends AbstractFilterType
{
    /**
     * {@inheritdoc}
     */
    public function buildFilterFrom(FormBuilderInterface $form, array $options)
    {
        $form
            ->add('operator', 'choice', [
                'label'   => false,
                'choices' => FilterOperator::getChoices($this->getOperators()),
            ])
            ->add('value', 'text', [
                'label' => false,
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getOperators()
    {
        return [
            FilterOperator::LIKE,
            FilterOperator::NOT_LIKE,
            FilterOperator::START_WITH,
            FilterOperator::NOT_START_WITH,
            FilterOperator::END_WITH,
            FilterOperator::NOT_START_WITH,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'text';
    }
}
