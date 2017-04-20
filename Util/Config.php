<?php

declare(strict_types=1);

namespace Ekyna\Component\Table\Util;

use Ekyna\Component\Table\Exception;

/**
 * Class Config
 * @package Ekyna\Component\Table\Util
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
final class Config
{
    public const SELECTION_SINGLE   = 'single';
    public const SELECTION_MULTIPLE = 'multiple';


    /**
     * Returns whether the given selection mode is valid or not.
     *
     * @param string $mode  The selection mode.
     * @param bool   $throw Whether to throw an exception if the given mode is not valid.
     *
     * @return bool
     */
    public static function isValidSelectionMode(string $mode, bool $throw = false): bool
    {
        $valid = in_array($mode, [
            self::SELECTION_SINGLE,
            self::SELECTION_MULTIPLE,
        ], true);

        if (!$valid && $throw) {
            throw new Exception\InvalidArgumentException("Invalid selection mode '{$mode}'.");
        }

        return $valid;
    }

    /**
     * Validates whether the given variable is a valid name.
     *
     * @param string $name The tested name
     *
     * @throws Exception\UnexpectedTypeException If the name is not a string.
     * @throws Exception\InvalidArgumentException If the name contains invalid characters.
     */
    public static function validateName(string $name): void
    {
        if (!is_null($name) && !is_string($name)) {
            throw new Exception\UnexpectedTypeException($name, ['string', 'integer', 'null']);
        }

        if (!preg_match('/^[a-zA-Z0-9_]+$/', $name)) {
            throw new Exception\InvalidArgumentException(sprintf(
                'The name "%s" contains illegal characters. Names should only contain letters, numbers and underscores.',
                $name
            ));
        }
    }

    /**
     * Disabled constructor.
     */
    private function __construct()
    {
    }
}
