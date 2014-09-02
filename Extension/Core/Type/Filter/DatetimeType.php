<?php

namespace Ekyna\Component\Table\Extension\Core\Type\Filter;

use Ekyna\Component\Table\AbstractFilterType;
use Ekyna\Component\Table\Util\FilterOperator;
use Symfony\Component\Form\FormBuilderInterface;
use Ekyna\Component\Table\TableView;
use Ekyna\Component\Table\View\ActiveFilter;

/**
 * Class DatetimeType
 * @package Ekyna\Component\Table\Extension\Core\Type\Filter
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class DatetimeType extends AbstractFilterType
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
            ->add('value', 'datetime', array(
                'label' => false,
                'input'  => 'datetime',
                'widget' => 'single_text',
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function buildActiveFilters(TableView $view, array $datas)
    {
        $activeFilter = new ActiveFilter();
        $activeFilter->setVars(array(
            'full_name' => $datas['full_name'],
            'id'        => $datas['id'],
            'field'     => $datas['label'],
            'operator'  => FilterOperator::getLabel($datas['operator']),
            'value'     => $datas['value']->format('d/m/Y H:i')
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
            FilterOperator::LOWER_THAN,
            FilterOperator::LOWER_THAN_OR_EQUAL,
            FilterOperator::GREATER_THAN,
            FilterOperator::GREATER_THAN_OR_EQUAL,
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
    	return 'datetime';
    }
}
