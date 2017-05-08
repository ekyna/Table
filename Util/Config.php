<?php

namespace Ekyna\Component\Table\Util;

use Ekyna\Component\Table\Exception;

/**
 * Class Config
 * @package Ekyna\Component\Table\Util
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
abstract class Config
{
    const SELECTION_SINGLE   = 'single';
    const SELECTION_MULTIPLE = 'multiple';


    /**
     * Returns whether the given selection mode is valid or not.
     *
     * @param string $mode  The selection mode.
     * @param bool   $throw Whether to throw an exception if the given mode is not valid.
     *
     * @return bool
     */
    static public function isValidSelectionMode($mode, $throw = false)
    {
        $valid = in_array($mode, [
            static::SELECTION_SINGLE,
            static::SELECTION_MULTIPLE,
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
     * @throws Exception\UnexpectedTypeException  If the name is not a string.
     * @throws Exception\InvalidArgumentException If the name contains invalid characters.
     */
    static public function validateName($name)
    {
        if (null !== $name && !is_string($name)) {
            throw new Exception\UnexpectedTypeException($name, 'string, integer or null');
        }

        if (!preg_match('/^[a-zA-Z0-9_]+$/', $name)) {
            throw new Exception\InvalidArgumentException(sprintf(
                'The name "%s" contains illegal characters. Names should only contain letters, numbers and underscores.',
                $name
            ));
        }
    }
}
