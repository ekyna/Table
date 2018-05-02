<?php

namespace Ekyna\Component\Table\Bridge\Doctrine\ORM\Extension\Filter;

use Doctrine\DBAL\Types\Type;
use Ekyna\Component\Table\Bridge\Doctrine\ORM\Source\EntityAdapter;
use Ekyna\Component\Table\Bridge\Doctrine\ORM\Util\FilterUtil;
use Ekyna\Component\Table\Context\ActiveFilter;
use Ekyna\Component\Table\Extension\AbstractFilterTypeExtension;
use Ekyna\Component\Table\Extension\Core\Type\Filter\DateTimeType;
use Ekyna\Component\Table\Filter\FilterInterface;
use Ekyna\Component\Table\Source\AdapterInterface;
use Ekyna\Component\Table\Util\FilterOperator;

/**
 * Class DatetimeFilterTypeExtension
 * @package Ekyna\Component\Table\Bridge\Doctrine\ORM\Extension\Filter
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class DatetimeFilterTypeExtension extends AbstractFilterTypeExtension
{
    /**
     * @inheritDoc
     */
    public function applyFilter(AdapterInterface $adapter, FilterInterface $filter, ActiveFilter $activeFilter, array $options)
    {
        if (!$adapter instanceof EntityAdapter) {
            return false;
        }

        $qb = $adapter->getQueryBuilder();
        $property = $adapter->getQueryBuilderPath($filter->getConfig()->getPropertyPath());

        $operator = $activeFilter->getOperator();

        if (in_array($operator, [FilterOperator::EQUAL, FilterOperator::NOT_EQUAL])) {
            /** @var \DateTime $start */
            $start = clone $activeFilter->getValue();
            $start->setTime(0, 0, 0);

            /** @var \DateTime $end */
            $end = clone $start;
            $end->setTime(23, 59, 59);

            if ($operator === FilterOperator::EQUAL) {
                $startOperator = FilterOperator::GREATER_THAN_OR_EQUAL;
                $endOperator = FilterOperator::LOWER_THAN_OR_EQUAL;
            } else {
                $startOperator = FilterOperator::LOWER_THAN;
                $endOperator = FilterOperator::GREATER_THAN;
            }

            $parameterStart = FilterUtil::buildParameterName($filter->getName() . '_start');
            $expressionStart = FilterUtil::buildExpression($property, $startOperator, $parameterStart);

            $parameterEnd = FilterUtil::buildParameterName($filter->getName() . '_end');
            $expressionEnd = FilterUtil::buildExpression($property, $endOperator, $parameterEnd);

            if ($operator === FilterOperator::EQUAL) {
                $expression = $qb->expr()->andX($expressionStart, $expressionEnd);
            } else {
                $expression = $qb->expr()->orX($expressionStart, $expressionEnd);
            }

            $adapter
                ->getQueryBuilder()
                ->andWhere($expression)
                ->setParameter($parameterStart, $start, Type::DATETIME)
                ->setParameter($parameterEnd, $end, Type::DATETIME);

            return true;
        }

        $parameter = FilterUtil::buildParameterName($filter->getName());
        $expression = FilterUtil::buildExpression($property, $operator = $activeFilter->getOperator(), $parameter);

        /** @var \DateTime $date */
        $date = clone $activeFilter->getValue();

        if (!$options['time']) {
            if ($operator === FilterOperator::LOWER_THAN_OR_EQUAL) {
                $date->setTime(23, 59, 59);
            } else {
                $date->setTime(0, 0, 0);
            }
        }

        $qb
            ->andWhere($expression)
            ->setParameter($parameter, $date, Type::DATETIME);

        return true;
    }

    /**
     * @inheritDoc
     */
    public function getExtendedType()
    {
        return DateTimeType::class;
    }
}
