<?php

namespace Ekyna\Component\Table\Util;

use Ekyna\Component\Table\Exception\InvalidArgumentException;

/**
 * Class ColumnSort
 * @package Ekyna\Component\Table\Util
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
abstract class ColumnSort
{
    const NONE = 'none';
    const ASC  = 'asc';
    const DESC = 'desc';


    /**
     * Returns whether the given direction is valid.
     *
     * @param string $direction
     * @param bool $throw
     *
     * @return bool
     */
    static public function isValid($direction, $throw = false)
    {
        $valid = in_array($direction, [
            static::NONE,
            static::ASC,
            static::DESC,
        ], true);

        if (!$valid && $throw) {
            throw new InvalidArgumentException(sprintf("The direction '%s' is not valid.", $direction));
        }

        return $valid;
    }
}
