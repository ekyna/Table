<?php

declare(strict_types=1);

namespace Ekyna\Component\Table\Exception;

use InvalidArgumentException as BaseException;

/**
 * Base InvalidArgumentException for the Table component.
 */
class InvalidArgumentException extends BaseException implements ExceptionInterface
{
}
