<?php

namespace Ekyna\Component\Table\Util;

use Ekyna\Component\Table\Exception\InvalidArgumentException;

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

    public static function getExpression($operator)
    {
        self::isValid($operator, true);

        switch(intval($operator)) {
        	case 2 : return '!='; break;
        	case 3 : return '<'; break;
        	case 4 : return '<='; break;
        	case 5 : return '>'; break;
        	case 6 : return '>='; break;
        	case 7 : return ' IN '; break;
        	case 8 : return ' NOT IN '; break;
        	case 9 :
        	case 11 :
        	case 13 : return ' LIKE '; break;
        	case 10 : 
        	case 12 : 
        	case 14 : return ' NOT LIKE '; break;
        	default : return '=';
        }
    }

    public static function getLabel($operator)
    {
        self::isValid($operator, true);

        switch(intval($operator)) {
        	case 2  : return 'est différent de'; break;
        	case 3  : return 'est inférieur à'; break;
        	case 4  : return 'est inférieur ou égal à'; break;
        	case 5  : return 'est supérieur à'; break;
        	case 6  : return 'est supérieur ou égal à'; break;
        	case 7  : return 'est parmis'; break;
        	case 8  : return 'n\'est pas parmis'; break;
        	case 9  : return 'contient'; break;
        	case 10  : return 'ne contient pas'; break;
        	case 11  : return 'commence par'; break;
        	case 12  : return 'ne commence pas par'; break;
        	case 13  : return 'se termine par'; break;
        	case 14  : return 'ne se termine pas par'; break;
        	default : return 'est égal à';
        }
    }

    public static function formatValue($operator, $value)
    {
        switch(intval($operator)) {
        	case 7  : 
        	case 8  : return sprintf('(%s)', implode(',', $value)); break;
        	case 9  : 
        	case 10  : return sprintf('%%%s%%', $value); break;
        	case 11  : 
        	case 12  : return sprintf('%s%%', $value); break;
        	case 13  : 
        	case 14  : return sprintf('%%%s', $value); break;
        	default : return $value;
        }
    }
    
    public static function getChoices($operators)
    {
        $choices = array();
        foreach($operators as $operator) {
            $choices[$operator] = self::getLabel($operator);
        }
        return $choices;
    }
}