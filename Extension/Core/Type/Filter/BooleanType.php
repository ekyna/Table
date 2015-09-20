<?php

namespace Ekyna\Component\Table\Extension\Core\Type\Filter;

use Doctrine\ORM\QueryBuilder;
use Ekyna\Component\Table\AbstractFilterType;
use Ekyna\Component\Table\TableView;
use Ekyna\Component\Table\Util\FilterOperator;
use Ekyna\Component\Table\View\ActiveFilter;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class BooleanType
 * @package Ekyna\Component\Table\Extension\Core\Type\Filter
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class BooleanType extends AbstractFilterType
{
    const MODE_DEFAULT     = 'default';
    const MODE_IS_NULL     = 'is_null';
    const MODE_IS_NOT_NULL = 'is_not_null';


    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolverInterface $resolver)
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefaults(array(
                'mode' => self::MODE_DEFAULT,
            ))
            ->setAllowedTypes(array(
                'mode' => 'string',
            ))
            ->setAllowedValues(array(
                'mode' => array(self::MODE_DEFAULT, self::MODE_IS_NULL, self::MODE_IS_NOT_NULL),
            ))
        ;
    }

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
    public function buildActiveFilter(TableView $view, array $data, array $options)
    {
        $activeFilter = new ActiveFilter();
        $activeFilter->setVars(array(
            'full_name' => $data['full_name'],
            'id' => $data['id'],
            'field' => $data['label'],
            'operator' => FilterOperator::getLabel($data['operator']),
            'value' => $data['value'] ? 'ekyna_core.value.yes' : 'ekyna_core.value.no',
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

        if ($options['mode'] === self::MODE_DEFAULT) {
            $qb
                ->andWhere(FilterOperator::buildExpression(
                    $alias.'.'.$data['property_path'],
                    $data['operator'],
                    ':filter_'.self::$filterCount
                ))
                ->setParameter(
                    'filter_'.self::$filterCount,
                    FilterOperator::buildParameter($data['operator'], $data['value'])
                )
            ;
        } else {
            $value = $options['mode'] === self::MODE_IS_NULL ? $data['value'] : !$data['value'];
            $operator = $value ? FilterOperator::IS_NULL : FilterOperator::IS_NOT_NULL;

            $qb->andWhere(FilterOperator::buildExpression(
                $alias.'.'.$data['property_path'],
                $operator,
                ':filter_'.self::$filterCount
            ));
        }
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
