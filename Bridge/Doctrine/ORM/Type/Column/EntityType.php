<?php

namespace Ekyna\Component\Table\Bridge\Doctrine\ORM\Type\Column;

use Ekyna\Component\Table\Bridge\Doctrine\ORM\Source\EntityAdapter;
use Ekyna\Component\Table\Column\AbstractColumnType;
use Ekyna\Component\Table\Column\ColumnBuilderInterface;
use Ekyna\Component\Table\Column\ColumnInterface;
use Ekyna\Component\Table\Context\ActiveSort;
use Ekyna\Component\Table\Extension\Core\Type\Column\PropertyType;
use Ekyna\Component\Table\Source\AdapterInterface;
use Ekyna\Component\Table\Source\RowInterface;
use Ekyna\Component\Table\View\CellView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class EntityType
 * @package Ekyna\Component\Table\Bridge\Doctrine\ORM\Type\Column
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class EntityType extends AbstractColumnType
{
    /**
     * @inheritDoc
     */
    public function buildColumn(ColumnBuilderInterface $builder, array $options)
    {
        if (is_callable($options['entity_label'])) {
            $builder->setSortable(false);
        }
    }

    /**
     * @inheritDoc
     */
    public function buildCellView(CellView $view, ColumnInterface $column, RowInterface $row, array $options)
    {
        $value = $row->getData($column->getConfig()->getPropertyPath());

        if ($value instanceof \IteratorAggregate) {
            $value = (array)$value->getIterator();
        } elseif (is_null($value)) {
            $value = [];
        } elseif (!is_array($value)) {
            $value = [$value];
        }

        $entities = [];

        if (!empty($value)) {
            $entityLabel = $options['entity_label'];
            if (is_callable($entityLabel)) {
                $transform = $entityLabel;
            } elseif (null === $entityLabel) {
                $transform = function ($entity) {
                    return (string)$entity;
                };
            } else {
                $accessor = $row->getPropertyAccessor();
                $transform = function ($entity) use ($accessor, $entityLabel) {
                    return $accessor->getValue($entity, $entityLabel);
                };
            }

            foreach ($value as $entity) {
                $entities[] = [
                    'value' => $entity,
                    'label' => $transform($entity),
                ];
            }
        }

        $view->vars['value'] = $entities;
    }

    /**
     * @inheritDoc
     */
    public function applySort(AdapterInterface $adapter, ColumnInterface $column, ActiveSort $activeSort, array $options)
    {
        if ($adapter instanceof EntityAdapter) {
            /**
             * 'entity_label' option should be a string, as sorting is disabled
             * if it is a callable {@see EntityType::buildColumn()}
             */
            $propertyPath = $column->getConfig()->getPropertyPath() . '.' . $options['entity_label'];

            $property = $adapter->getQueryBuilderPath($propertyPath);

            $adapter
                ->getQueryBuilder()
                ->addOrderBy($property, $activeSort->getDirection());

            return true;
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('entity_label', null)
            ->setAllowedTypes('entity_label', ['null', 'string', 'callable']);
    }

    /**
     * @inheritDoc
     */
    public function getBlockPrefix()
    {
        return 'choice';
    }

    /**
     * @inheritDoc
     */
    public function getParent()
    {
        return PropertyType::class;
    }
}
