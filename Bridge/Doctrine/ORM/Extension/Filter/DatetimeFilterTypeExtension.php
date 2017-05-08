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

        $property = $adapter->getQueryBuilderPath($filter->getConfig()->getPropertyPath());
        $parameter = FilterUtil::buildParameterName($filter->getName());
        $expression = FilterUtil::buildExpression($property, $activeFilter->getOperator(), $parameter);

        $adapter
            ->getQueryBuilder()
            ->andWhere($expression)
            ->setParameter($parameter, $activeFilter->getValue(), Type::DATETIME);

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
