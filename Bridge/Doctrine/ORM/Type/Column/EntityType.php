<?php

declare(strict_types=1);

namespace Ekyna\Component\Table\Bridge\Doctrine\ORM\Type\Column;

use Ekyna\Component\Table\Bridge\Doctrine\ORM\Source\EntityAdapter;
use Ekyna\Component\Table\Column\AbstractColumnType;
use Ekyna\Component\Table\Column\ColumnInterface;
use Ekyna\Component\Table\Context\ActiveSort;
use Ekyna\Component\Table\Extension\Core\Type\Column\PropertyType;
use Ekyna\Component\Table\Source\AdapterInterface;
use Ekyna\Component\Table\Source\RowInterface;
use Ekyna\Component\Table\View\CellView;
use IteratorAggregate;
use Symfony\Component\OptionsResolver\OptionsResolver;

use function array_map;
use function implode;
use function is_array;
use function is_callable;
use function is_null;
use function iterator_to_array;

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
    public function buildCellView(CellView $view, ColumnInterface $column, RowInterface $row, array $options): void
    {
        $view->vars['value'] = $this->getEntities($column, $row, $options);
    }

    /**
     * @inheritDoc
     */
    public function applySort(
        AdapterInterface $adapter,
        ColumnInterface  $column,
        ActiveSort       $activeSort,
        array            $options
    ): bool {
        if (!$adapter instanceof EntityAdapter) {
            return false;
        }

        /**
         * 'entity_label' option should be a string, as sorting is disabled
         * if it is a callable {@see EntityType::buildColumn()}
         */

        $property = (string)$column->getConfig()->getPropertyPath();
        $addPrefix = function (array $properties) use ($property) {
            return array_map(fn(string $p) => trim($property . '.' . $p, '.'), $properties);
        };

        if (!empty($options['sort_property'])) {
            $properties = $addPrefix((array)$options['sort_property']);
        } elseif (!empty($options['entity_label'])) {
            $properties = $addPrefix([$options['entity_label']]);
        } else {
            $properties = [$property . '.id'];
        }

        $qb = $adapter->getQueryBuilder();
        foreach ($properties as $property) {
            $sort = $adapter->getQueryBuilderPath($property);

            $qb->addOrderBy($sort, $activeSort->getDirection());
        }

        return true;
    }

    public function export(ColumnInterface $column, RowInterface $row, array $options): ?string
    {
        $result = array_map(
            fn(array $e): string => $e['label'],
            $this->getEntities($column, $row, $options)
        );

        return implode(', ', $result);
    }

    private function getEntities(ColumnInterface $column, RowInterface $row, array $options): array
    {
        $value = $row->getData($column->getConfig()->getPropertyPath());

        if ($value instanceof IteratorAggregate) {
            /** @noinspection PhpUnhandledExceptionInspection */
            $value = iterator_to_array($value->getIterator());
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

        return $entities;
    }

    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'entity_label'  => null,
                'sort_property' => null,
            ])
            ->setAllowedTypes('entity_label', ['null', 'string', 'callable'])
            ->setAllowedTypes('sort_property', ['null', 'string', 'array']);
    }

    /**
     * @inheritDoc
     */
    public function getBlockPrefix(): string
    {
        return 'choice';
    }

    /**
     * @inheritDoc
     */
    public function getParent(): ?string
    {
        return PropertyType::class;
    }
}
