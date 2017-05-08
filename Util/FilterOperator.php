<?php

namespace Ekyna\Component\Table\Util;

use Ekyna\Component\Table\Exception\InvalidArgumentException;

/**
 * Class FilterOperator
 * @package Ekyna\Component\Table\Util
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
abstract class FilterOperator
{
    const EQUAL                 = 1;
    const NOT_EQUAL             = 2;
    const LOWER_THAN            = 3;
    const LOWER_THAN_OR_EQUAL   = 4;
    const GREATER_THAN          = 5;
    const GREATER_THAN_OR_EQUAL = 6;
    const IN                    = 7;
    const NOT_IN                = 8;
    const MEMBER                = 9;
    const NOT_MEMBER            = 10;
    const LIKE                  = 11;
    const NOT_LIKE              = 12;
    const START_WITH            = 13;
    const NOT_START_WITH        = 14;
    const END_WITH              = 15;
    const NOT_END_WITH          = 16;
    const IS_NULL               = 17;
    const IS_NOT_NULL           = 18;


    /**
     * @deprecated
     * @todo remove
     */
    static public function buildExpression()
    {
        throw new \Exception("Please use FilterUtil::buildExpression();");
    }

    /**
     * @deprecated
     * @todo remove
     */
    static public function buildParameter()
    {
        throw new \Exception("Please use FilterUtil::buildParameterValue();");
    }

    /**
     * Returns whether the operator is valid or not.
     *
     * @param string $operator
     * @param bool   $throwException
     *
     * @return bool
     */
    static public function isValid($operator, $throwException = false)
    {
        $operator = intval($operator);
        if ($operator > 0 && $operator <= static::IS_NOT_NULL) {
            return true;
        }

        if ($throwException) {
            throw new InvalidArgumentException('Unexpected filter operator.');
        }

        return false;
    }

    /**
     * Returns the label for the given operator.
     *
     * @param string $operator
     *
     * @return string
     */
    static public function getLabel($operator)
    {
        static::isValid($operator, true);

        // TODO translations
        switch (intval($operator)) {
            case static::NOT_EQUAL:
                return 'est différent de';
            case static::LOWER_THAN:
                return 'est inférieur à';
            case static::LOWER_THAN_OR_EQUAL:
                return 'est inférieur ou égal à';
            case static::GREATER_THAN:
                return 'est supérieur à';
            case static::GREATER_THAN_OR_EQUAL:
                return 'est supérieur ou égal à';
            case static::IN:
            case static::MEMBER:
                return 'est parmi';
            case static::NOT_IN:
            case static::NOT_MEMBER:
                return 'n\'est pas parmi';
            case static::LIKE:
                return 'contient';
            case static::NOT_LIKE:
                return 'ne contient pas';
            case static::START_WITH:
                return 'commence par';
            case static::NOT_START_WITH:
                return 'ne commence pas par';
            case static::END_WITH:
                return 'se termine par';
            case static::NOT_END_WITH:
                return 'ne se termine pas par';
            default:
                return 'est égal à';
        }
    }

    /**
     * Returns the available operators choices.
     *
     * @param array $operators
     *
     * @return array
     */
    static public function getChoices(array $operators)
    {
        $choices = [];
        foreach ($operators as $operator) {
            $choices[static::getLabel($operator)] = $operator;
        }

        return $choices;
    }
}
