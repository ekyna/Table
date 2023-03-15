<?php

declare(strict_types=1);

namespace Ekyna\Component\Table\Util;

use Ekyna\Component\Table\Exception\InvalidArgumentException;

/**
 * Class FilterOperator
 * @package Ekyna\Component\Table\Util
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
final class FilterOperator
{
    public const EQUAL                 = 1;
    public const NOT_EQUAL             = 2;
    public const LOWER_THAN            = 3;
    public const LOWER_THAN_OR_EQUAL   = 4;
    public const GREATER_THAN          = 5;
    public const GREATER_THAN_OR_EQUAL = 6;
    public const IN                    = 7;
    public const NOT_IN                = 8;
    public const MEMBER                = 9;
    public const NOT_MEMBER            = 10;
    public const LIKE                  = 11;
    public const NOT_LIKE              = 12;
    public const START_WITH            = 13;
    public const NOT_START_WITH        = 14;
    public const END_WITH              = 15;
    public const NOT_END_WITH          = 16;
    public const IS_NULL               = 17;
    public const IS_NOT_NULL           = 18;


    /**
     * Returns whether the operator is valid or not.
     *
     * @param int  $operator
     * @param bool $throwException
     *
     * @return bool
     */
    public static function isValid(int $operator, bool $throwException = false): bool
    {
        $operator = intval($operator);
        if ($operator > 0 && $operator <= self::IS_NOT_NULL) {
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
     * @param int $operator
     *
     * @return string
     */
    public static function getLabel(int $operator): string
    {
        self::isValid($operator, true);

        // TODO translations
        switch (intval($operator)) {
            case self::NOT_EQUAL:
                return 'est différent de';
            case self::LOWER_THAN:
                return 'est inférieur à';
            case self::LOWER_THAN_OR_EQUAL:
                return 'est inférieur ou égal à';
            case self::GREATER_THAN:
                return 'est supérieur à';
            case self::GREATER_THAN_OR_EQUAL:
                return 'est supérieur ou égal à';
            case self::IN:
            case self::MEMBER:
                return 'est parmi';
            case self::NOT_IN:
            case self::NOT_MEMBER:
                return 'n\'est pas parmi';
            case self::LIKE:
                return 'contient';
            case self::NOT_LIKE:
                return 'ne contient pas';
            case self::START_WITH:
                return 'commence par';
            case self::NOT_START_WITH:
                return 'ne commence pas par';
            case self::END_WITH:
                return 'se termine par';
            case self::NOT_END_WITH:
                return 'ne se termine pas par';
            case self::IS_NULL:
                return 'est indéfini';
            case self::IS_NOT_NULL:
                return 'est défini';
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
    public static function getChoices(array $operators): array
    {
        $choices = [];
        foreach ($operators as $operator) {
            $choices[self::getLabel($operator)] = $operator;
        }

        return $choices;
    }

    /**
     * Disabled constructor.
     */
    private function __construct()
    {
    }
}
