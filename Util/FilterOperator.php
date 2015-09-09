<?php

namespace Ekyna\Component\Table\Util;

use Doctrine\ORM\Query\Expr;
use Ekyna\Component\Table\Exception\InvalidArgumentException;

/**
 * Class FilterOperator
 * @package Ekyna\Component\Table\Util
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
final class FilterOperator
{
    const EQUAL                 = 1;
    const NOT_EQUAL             = 2;
    const LOWER_THAN            = 3;
    const LOWER_THAN_OR_EQUAL   = 4;
    const GREATER_THAN          = 5;
    const GREATER_THAN_OR_EQUAL = 6;
    const IN                    = 7;
    const NOT_IN                = 8;
    const LIKE                  = 9;
    const NOT_LIKE              = 10;
    const START_WITH            = 11;
    const NOT_START_WITH        = 12;
    const END_WITH              = 13;
    const NOT_END_WITH          = 14;

    /**
     * Returns whether the operator is valid or not.
     *
     * @param $operator
     * @param bool $throwException
     * @return bool
     */
    public static function isValid($operator, $throwException = false)
    {
        $operator = intval($operator);
        if($operator > 0 && $operator <= self::NOT_END_WITH) {
            return true;
        }
        if($throwException) {
            throw new InvalidArgumentException('Unexpected filter operator.');
        }
        return false;
    }

    /**
     * Returns the label for the given operator.
     *
     * @param $operator
     * @return string
     */
    public static function getLabel($operator)
    {
        self::isValid($operator, true);

        // TODO translations
        switch(intval($operator)) {
            case self::NOT_EQUAL:             return 'est différent de';
            case self::LOWER_THAN:            return 'est inférieur à';
            case self::LOWER_THAN_OR_EQUAL:   return 'est inférieur ou égal à';
            case self::GREATER_THAN:          return 'est supérieur à';
            case self::GREATER_THAN_OR_EQUAL: return 'est supérieur ou égal à';
            case self::IN:                    return 'est parmi';
            case self::NOT_IN:                return 'n\'est pas parmi';
            case self::LIKE:                  return 'contient';
            case self::NOT_LIKE:              return 'ne contient pas';
            case self::START_WITH:            return 'commence par';
            case self::NOT_START_WITH:        return 'ne commence pas par';
            case self::END_WITH:              return 'se termine par';
            case self::NOT_END_WITH:          return 'ne se termine pas par';
            default:                          return 'est égal à';
        }
    }

    /**
     * Builds the orm expression.
     *
     * @param $property
     * @param $operator
     * @param $parameter
     * @return Expr\Comparison|Expr\Func
     */
    public static function buildExpression($property, $operator, $parameter)
    {
        self::isValid($operator, true);

        $expr = new Expr();
        switch(intval($operator)) {
            case self::NOT_EQUAL:
                return $expr->neq($property, $parameter);
            case self::LOWER_THAN:
                return $expr->lt($property, $parameter);
            case self::LOWER_THAN_OR_EQUAL:
                return $expr->lte($property, $parameter);
            case self::GREATER_THAN:
                return $expr->gt($property, $parameter);
            case self::GREATER_THAN_OR_EQUAL:
                return $expr->gte($property, $parameter);
            case self::IN:
                return $expr->in($property, $parameter);
            case self::NOT_IN:
                return $expr->notIn($property, $parameter);
            case self::LIKE:
                return $expr->like($property, $parameter);
            case self::NOT_LIKE:
                return $expr->notLike($property, $parameter);
            case self::START_WITH:
                return $expr->like($property, $parameter);
            case self::NOT_START_WITH:
                return $expr->notLike($property, $parameter);
            case self::END_WITH:
                return $expr->like($property, $parameter);
            case self::NOT_END_WITH:
                return $expr->notLike($property, $parameter);
            default  :
                return $expr->eq($property, $parameter);
        }
    }

    /**
     * Builds the parameter value.
     *
     * @param $operator
     * @param $value
     * @return Expr\Literal
     */
    public static function buildParameter($operator, $value)
    {
        self::isValid($operator, true);

        switch(intval($operator)) {
            case self::LIKE:
            case self::NOT_LIKE:
                return sprintf('%%%s%%', $value);
            case self::START_WITH:
            case self::NOT_START_WITH:
                return sprintf('%s%%', $value);
            case self::END_WITH:
            case self::NOT_END_WITH:
                return sprintf('%%%s', $value);
            default:
                return $value;
        }
    }

    /**
     * Returns the available operator choices.
     *
     * @param $operators
     * @return array
     */
    public static function getChoices($operators)
    {
        $choices = array();
        foreach($operators as $operator) {
            $choices[$operator] = self::getLabel($operator);
        }
        return $choices;
    }
}
