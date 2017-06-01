<?php

namespace Ekyna\Component\Table\Extension\Core\Type\Filter;

use Ekyna\Component\Table\Filter\AbstractFilterType;
use Ekyna\Component\Table\Filter\FilterInterface;
use Ekyna\Component\Table\Util\FilterOperator;
use Symfony\Component\Form\Extension\Core\Type as FormType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class TextType
 * @package Ekyna\Component\Table\Extension\Core\Type\Filter
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class TextType extends AbstractFilterType
{
    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, FilterInterface $filter, array $options)
    {
        $builder
            ->add('operator', FormType\ChoiceType::class, [
                'label'   => false,
                'required' => true,
                'choices' => FilterOperator::getChoices([
                    FilterOperator::LIKE,
                    FilterOperator::NOT_LIKE,
                    FilterOperator::START_WITH,
                    FilterOperator::NOT_START_WITH,
                    FilterOperator::END_WITH,
                    FilterOperator::NOT_START_WITH,
                ]),
            ])
            ->add('value', FormType\TextType::class, [
                'label' => false,
                'required' => true,
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
