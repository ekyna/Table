<?php

namespace Ekyna\Component\Table\Bridge\Doctrine\ORM\Util;

use Doctrine\ORM\Query\Expr;
use Ekyna\Component\Table\Util\FilterOperator;

/**
 * Class FilterUtil
 * @package Ekyna\Component\Table\Bridge\Doctrine\ORM\Util
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
abstract class FilterUtil
{
    /**
     * @var int
     */
    static private $count = 0;


    /**
     * Builds the query builder expression.
     *
     * @param string $property
     * @param string $operator
     * @param string $parameter
     *
     * @return string|Expr\Comparison|Expr\Func
     */
    static public function buildExpression($property, $operator, $parameter = null)
    {
        FilterOperator::isValid($operator, true);

        $expr = new Expr();
        switch (intval($operator)) {
            case FilterOperator::NOT_EQUAL:
                return $expr->neq($property, $parameter);
            case FilterOperator::LOWER_THAN:
                return $expr->lt($property, $parameter);
            case FilterOperator::LOWER_THAN_OR_EQUAL:
                return $expr->lte($property, $parameter);
            case FilterOperator::GREATER_THAN:
                return $expr->gt($property, $parameter);
            case FilterOperator::GREATER_THAN_OR_EQUAL:
                return $expr->gte($property, $parameter);
            case FilterOperator::IN:
                return $expr->in($property, $parameter);
            case FilterOperator::NOT_IN:
                return $expr->notIn($property, $parameter);
            case FilterOperator::MEMBER:
                return $expr->isMemberOf($parameter, $property);
            case FilterOperator::NOT_MEMBER:
                return $expr->not($expr->notIn($property, $parameter));
            case FilterOperator::LIKE:
                return $expr->like($property, $parameter);
            case FilterOperator::NOT_LIKE:
                return $expr->notLike($property, $parameter);
            case FilterOperator::START_WITH:
                return $expr->like($property, $parameter);
            case FilterOperator::NOT_START_WITH:
                return $expr->notLike($property, $parameter);
            case FilterOperator::END_WITH:
                return $expr->like($property, $parameter);
            case FilterOperator::NOT_END_WITH:
                return $expr->notLike($property, $parameter);
            case FilterOperator::IS_NULL:
                return $expr->isNull($property);
            case FilterOperator::IS_NOT_NULL:
                return $expr->isNotNull($property);
            default:
                return $expr->eq($property, $parameter);
        }
    }

    /**
     * Builds the query builder parameter name.
     *
     * @param string $name
     *
     * @return string
     */
    static public function buildParameterName($name = null)
    {
        return ':' . ($name ?: 'filter_') . static::$count++;
    }

    /**
     * Builds the query builder parameter value.
     *
     * @param string $operator
     * @param mixed  $value
     *
     * @return Expr\Literal|mixed
     */
    static public function buildParameterValue($operator, $value)
    {
        FilterOperator::isValid($operator, true);

        switch (intval($operator)) {
            case FilterOperator::LIKE:
            case FilterOperator::NOT_LIKE:
                return sprintf('%%%s%%', $value);
            case FilterOperator::START_WITH:
            case FilterOperator::NOT_START_WITH:
                return sprintf('%s%%', $value);
            case FilterOperator::END_WITH:
            case FilterOperator::NOT_END_WITH:
                return sprintf('%%%s', $value);
            default:
                return $value;
        }
    }
}
