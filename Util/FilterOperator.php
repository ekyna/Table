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
        if($operator > 0 && $operator <= 14) {
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
        	case 2  : return 'est différent de';
        	case 3  : return 'est inférieur à';
        	case 4  : return 'est inférieur ou égal à';
        	case 5  : return 'est supérieur à';
        	case 6  : return 'est supérieur ou égal à';
        	case 7  : return 'est parmis';
        	case 8  : return 'n\'est pas parmis';
        	case 9  : return 'contient';
        	case 10  : return 'ne contient pas';
        	case 11  : return 'commence par';
        	case 12  : return 'ne commence pas par';
        	case 13  : return 'se termine par';
        	case 14  : return 'ne se termine pas par';
            default  : return 'est égal à';
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
        	case 2  :
                return $expr->neq($property, $parameter);
        	case 3  :
                return $expr->lt($property, $parameter);
        	case 4  :
                return $expr->lte($property, $parameter);
        	case 5  :
                return $expr->gt($property, $parameter);
        	case 6  :
                return $expr->gte($property, $parameter);
        	case 7  :
                return $expr->in($property, $parameter);
        	case 8  :
                return $expr->notIn($property, $parameter);
            case 9  :
                return $expr->like($property, $parameter);
        	case 10  :
                return $expr->notLike($property, $parameter);
        	case 11  :
                return $expr->like($property, $parameter);
        	case 12  :
                return $expr->notLike($property, $parameter);
        	case 13  :
                return $expr->like($property, $parameter);
        	case 14  :
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
            case 9  :
            case 10  :
                return sprintf('%%%s%%', $value);
            case 11  :
            case 12  :
                return sprintf('%s%%', $value);
            case 13  :
            case 14  :
                return sprintf('%%%s', $value);
            default  :
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
