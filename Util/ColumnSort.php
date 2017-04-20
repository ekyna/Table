<?php

declare(strict_types=1);

namespace Ekyna\Component\Table\Util;

use Ekyna\Component\Table\Exception\InvalidArgumentException;

/**
 * Class ColumnSort
 * @package Ekyna\Component\Table\Util
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
final class ColumnSort
{
    public const NONE = 'none';
    public const ASC  = 'asc';
    public const DESC = 'desc';


    /**
     * Returns whether the given direction is valid.
     *
     * @param string $direction
     * @param bool   $throw
     *
     * @return bool
     */
    public static function isValid(string $direction, bool $throw = false): bool
    {
        $valid = in_array($direction, [
            self::NONE,
            self::ASC,
            self::DESC,
        ], true);

        if (!$valid && $throw) {
            throw new InvalidArgumentException(sprintf("The direction '%s' is not valid.", $direction));
        }

        return $valid;
    }

    /**
     * Disabled constructor.
     */
    private function __construct()
    {
    }
}
