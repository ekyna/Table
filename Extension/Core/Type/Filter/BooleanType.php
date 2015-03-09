<?php

namespace Ekyna\Component\Table\Extension\Core\Type\Filter;

use Ekyna\Component\Table\AbstractFilterType;
use Ekyna\Component\Table\TableView;
use Ekyna\Component\Table\Util\FilterOperator;
use Ekyna\Component\Table\View\ActiveFilter;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class BooleanType
 * @package Ekyna\Component\Table\Extension\Core\Type\Filter
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class BooleanType extends AbstractFilterType
{
    /**
     * {@inheritdoc}
     */
    public function buildFilterFrom(FormBuilderInterface $form, array $options)
    {
        $form
            ->add('operator', 'choice', array(
                'label' => false,
                'choices' => FilterOperator::getChoices($this->getOperators())
            ))
            ->add('value', 'choice', array(
                'label' => false,
                'choices' => array(
                    '1' => 'ekyna_core.value.yes',
                    '0' => 'ekyna_core.value.no',
                )
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function buildActiveFilter(TableView $view, array $datas, array $options)
    {
        $activeFilter = new ActiveFilter();
        $activeFilter->setVars(array(
            'full_name' => $datas['full_name'],
            'id' => $datas['id'],
            'field' => $datas['label'],
            'operator' => FilterOperator::getLabel($datas['operator']),
            'value' => $datas['value'] ? 'ekyna_core.value.yes' : 'ekyna_core.value.no',
        ));
        $view->active_filters[] = $activeFilter;
    }

    /**
     * {@inheritdoc}
     */
    public function getOperators()
    {
        return array(
            FilterOperator::EQUAL,
            FilterOperator::NOT_EQUAL,
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'boolean';
    }
}
