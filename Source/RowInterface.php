<?php

namespace Ekyna\Component\Table\Source;

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
    public function getIdentifier();

    /**
     * Returns the row data, optionally for the given property path.
     *
     * @param string|false $propertyPath
     *
     * @return mixed
     */
    public function getData($propertyPath = false);

    /**
     * Returns the property accessor.
     *
     * @return \Symfony\Component\PropertyAccess\PropertyAccessorInterface
     */
    public function getPropertyAccessor();
}
