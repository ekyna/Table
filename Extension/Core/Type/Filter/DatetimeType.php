<?php

namespace Ekyna\Component\Table\Extension\Core\Type\Filter;

use Ekyna\Component\Table\AbstractFilterType;
use Ekyna\Component\Table\Util\FilterOperator;
use Symfony\Component\Form\FormBuilder;
use Ekyna\Component\Table\TableView;
use Ekyna\Component\Table\View\ActiveFilter;

/**
 * DatetimeType
 */
class DatetimeType extends AbstractFilterType
{
    public function buildFilterFrom(FormBuilder $form, array $options)
    {
        $form
            ->add('operator', 'choice', array(
                'label' => false,
                'choices' => FilterOperator::getChoices($this->getOperators())
            ))
            ->add('value', 'datetime', array(
                'label' => false,
                'input'  => 'datetime',
                'widget' => 'single_text',
            ))
        ;
    }

    public function buildActiveFilters(TableView $view, array $datas)
    {
        $activeFilter = new ActiveFilter();
        $activeFilter->setVars(array(
            'full_name' => $datas['full_name'],
            'id'        => $datas['id'],
            //'label'     => sprintf('<strong>%s</strong> %s "%s"', $datas['label'], FilterOperator::getLabel($datas['operator']), $datas['value']->format('d/m/Y H:i')),
            'field'     => $datas['label'],
            'operator'  => FilterOperator::getLabel($datas['operator']),
            'value'     => $datas['value']->format('d/m/Y H:i')
        ));
        $view->active_filters[] = $activeFilter;
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
    	return 'datetime';
    }
}