<?php

declare(strict_types=1);

namespace Ekyna\Component\Table\Source;

use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

/**
 * Interface RowInterface
 * @package Ekyna\Component\Table\Source
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
interface RowInterface
{
    /**
     * Returns the identifier.
     *
     * @return string
     */
    public function getIdentifier(): string;

    /**
     * Returns the row data, optionally for the given property path.
     *
     * @param string|null $propertyPath
     *
     * @return mixed
     */
    public function getData(?string $propertyPath);

    /**
     * Returns the property accessor.
     *
     * @return PropertyAccessorInterface
     */
    public function getPropertyAccessor(): PropertyAccessorInterface;
}
