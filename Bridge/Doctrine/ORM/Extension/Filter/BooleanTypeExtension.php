<?php

declare(strict_types=1);

namespace Ekyna\Component\Table\Bridge\Doctrine\ORM\Extension\Filter;

use Doctrine\DBAL\Types\Types;
use Ekyna\Component\Table\Bridge\Doctrine\ORM\Source\EntityAdapter;
use Ekyna\Component\Table\Bridge\Doctrine\ORM\Util\FilterUtil;
use Ekyna\Component\Table\Context\ActiveFilter;
use Ekyna\Component\Table\Extension\AbstractFilterTypeExtension;
use Ekyna\Component\Table\Extension\Core\Type\Filter\BooleanType;
use Ekyna\Component\Table\Filter\FilterInterface;
use Ekyna\Component\Table\Source\AdapterInterface;
use Ekyna\Component\Table\Util\FilterOperator;

/**
 * Class BooleanTypeExtension
 * @package Ekyna\Component\Table\Bridge\Doctrine\ORM\Extension\Filter
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class BooleanTypeExtension extends AbstractFilterTypeExtension
{
    /**
     * @inheritDoc
     */
    public function applyFilter(
        AdapterInterface $adapter,
        FilterInterface $filter,
        ActiveFilter $activeFilter,
        array $options
    ): bool {
        if (!$adapter instanceof EntityAdapter) {
            return false;
        }

        $property = $adapter->getQueryBuilderPath($filter->getConfig()->getPropertyPath());
        $operator = $activeFilter->getOperator();
        $value    = $activeFilter->getValue() === 'yes';

        if ($options['mode'] === BooleanType::MODE_DEFAULT) {
            $parameter  = FilterUtil::buildParameterName($filter->getName());
            $expression = FilterUtil::buildExpression($property, $operator, $parameter);

            $adapter
                ->getQueryBuilder()
                ->andWhere($expression)
                ->setParameter($parameter, $value, Types::BOOLEAN);
        } else {
            $value      = $options['mode'] === BooleanType::MODE_IS_NULL ? $value : !$value;
            $operator   = $value ? FilterOperator::IS_NULL : FilterOperator::IS_NOT_NULL;
            $expression = FilterUtil::buildExpression($property, $operator);

            $adapter
                ->getQueryBuilder()
                ->andWhere($expression);
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public static function getExtendedTypes(): array
    {
        return [BooleanType::class];
    }
}
