<?php

namespace Ekyna\Component\Table\Extension\Core\Type\Filter;

use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\QueryBuilder;
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
    public function buildActiveFilter(TableView $view, array $data, array $options)
    {
        /** @var \DateTime $date */
        $date = $data['value'];

        $activeFilter = new ActiveFilter();
        $activeFilter->setVars(array(
            'full_name' => $data['full_name'],
            'id'        => $data['id'],
            'field'     => $data['label'],
            'operator'  => FilterOperator::getLabel($data['operator']),
            'value'     => $date->format('d/m/Y H:i')
        ));
        $view->active_filters[] = $activeFilter;
    }

    /**
     * {@inheritdoc}
     */
    public function applyFilter(QueryBuilder $qb, array $data, array $options)
    {
        self::$filterCount++;
        $alias = $qb->getRootAliases()[0];
        $qb
            ->andWhere(FilterOperator::buildExpression(
                $alias.'.'.$data['property_path'],
                $data['operator'],
                ':filter_'.self::$filterCount
            ))
            ->setParameter(
                'filter_'.self::$filterCount,
                $data['value'],
                Type::DATETIME
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getOperators()
    {
        return array(
            FilterOperator::EQUAL,
            FilterOperator::NOT_EQUAL,
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
