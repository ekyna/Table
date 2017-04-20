<?php

declare(strict_types=1);

namespace Ekyna\Component\Table\Exception;

use Throwable;

use function array_slice;
use function count;
use function get_class;
use function gettype;
use function implode;
use function is_object;
use function reset;
use function sprintf;

/**
 * Class UnexpectedTypeException
 * @package Ekyna\Component\Table\Exception
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class UnexpectedTypeException extends InvalidArgumentException
{
    /**
     * Constructor.
     *
     * @param mixed           $value
     * @param string|string[] $types
     * @param int             $code
     * @param Throwable|null  $previous
     */
    public function __construct($value, $types, $code = 0, Throwable $previous = null)
    {
        $types = (array)$types;

        if (1 === $length = count($types)) {
            $types = reset($types);
        } elseif (2 === $length) {
            $types = implode(' or ', $types);
        } else {
            $types = implode(', ', array_slice($types, 0, $length - 2)) . ' or ' . $types[$length - 1];
        }

        $message = sprintf('Expected %s, got %s', $types, is_object($value) ? get_class($value) : gettype($value));

        parent::__construct($message, $code, $previous);
    }
}
