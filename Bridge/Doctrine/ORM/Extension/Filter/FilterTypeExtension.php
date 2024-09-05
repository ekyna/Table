<?php

declare(strict_types=1);

namespace Ekyna\Component\Table\Bridge\Doctrine\ORM\Extension\Filter;

use Ekyna\Component\Table\Bridge\Doctrine\ORM\Source\EntityAdapter;
use Ekyna\Component\Table\Bridge\Doctrine\ORM\Util\FilterUtil;
use Ekyna\Component\Table\Context\ActiveFilter;
use Ekyna\Component\Table\Exception\RuntimeException;
use Ekyna\Component\Table\Extension\AbstractFilterTypeExtension;
use Ekyna\Component\Table\Extension\Core\Type\Filter\FilterType;
use Ekyna\Component\Table\Filter\FilterInterface;
use Ekyna\Component\Table\Source\AdapterInterface;
use Ekyna\Component\Table\Util\FilterOperator;

/**
 * Class FilterTypeExtension
 * @package Ekyna\Component\Table\Bridge\Doctrine\ORM\Extension\Filter
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class FilterTypeExtension extends AbstractFilterTypeExtension
{
    /**
     * @inheritDoc
     */
    public function applyFilter(AdapterInterface $adapter, FilterInterface $filter, ActiveFilter $activeFilter, array $options): bool
    {
        if (!$adapter instanceof EntityAdapter) {
            return false;
        }

        $property = $adapter->getQueryBuilderPath($filter->getConfig()->getPropertyPath());
        $operator = $activeFilter->getOperator();
        $parameter = FilterUtil::buildParameterName($filter->getName());
        $expression = FilterUtil::buildExpression($property, $operator, $parameter);
        $value = FilterUtil::buildParameterValue($operator, $activeFilter->getValue());

        $qb = $adapter->getQueryBuilder();
        $qb->andWhere($expression);

        if (!FilterOperator::isValueNeeded($operator)) {
            if (!empty($value)) {
                throw new RuntimeException('Value should be empty.');
            }

            return true;
        }

        $qb->setParameter($parameter, $value);

        return true;
    }

    /**
     * @inheritDoc
     */
    public static function getExtendedTypes(): array
    {
        return [FilterType::class];
    }
}
