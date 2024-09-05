<?php

declare(strict_types=1);

namespace Ekyna\Component\Table\Util;

use Ekyna\Component\Table\Exception\InvalidArgumentException;

/**
 * Class FilterOperator
 * @package Ekyna\Component\Table\Util
 * @author  Étienne Dauvergne <contact@ekyna.com>
 *
 * @TODO    Turn into Enum
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
    public const IS_EMPTY              = 19;
    public const IS_NOT_EMPTY          = 20;


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
        if ($operator > 0 && $operator <= self::IS_NOT_NULL) {
            return true;
        }

        if ($throwException) {
            throw new InvalidArgumentException('Unexpected filter operator.');
        }

        return false;
    }

    /**
     * Returns whether a value is needed for the given operator.
     *
     * @param int $operator
     *
     * @return bool
     */
    public static function isValueNeeded(int $operator): bool
    {
        return match ($operator) {
            self::IS_NULL,
            self::IS_NOT_NULL,
            self::IS_EMPTY,
            self::IS_NOT_EMPTY => false,
            default            => true,
        };
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
        return match ($operator) {
            self::NOT_EQUAL                => 'est différent de',
            self::LOWER_THAN               => 'est inférieur à',
            self::LOWER_THAN_OR_EQUAL      => 'est inférieur ou égal à',
            self::GREATER_THAN             => 'est supérieur à',
            self::GREATER_THAN_OR_EQUAL    => 'est supérieur ou égal à',
            self::IN, self::MEMBER         => 'est parmi',
            self::NOT_IN, self::NOT_MEMBER => 'n\'est pas parmi',
            self::LIKE                     => 'contient',
            self::NOT_LIKE                 => 'ne contient pas',
            self::START_WITH               => 'commence par',
            self::NOT_START_WITH           => 'ne commence pas par',
            self::END_WITH                 => 'se termine par',
            self::NOT_END_WITH             => 'ne se termine pas par',
            self::IS_NULL                  => 'est indéfini',
            self::IS_NOT_NULL              => 'est défini',
            default                        => 'est égal à',
        };
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
