<?php

namespace Ekyna\Component\Table;

/**
 * Class ResolvedTypeFactory
 * @package Ekyna\Component\Table
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class ResolvedTypeFactory implements ResolvedTypeFactoryInterface
{
    /**
     * @inheritdoc
     */
    public function createResolvedTableType(
        TableTypeInterface $type,
        array $typeExtensions,
        ResolvedTableTypeInterface $parent = null
    ) {
        return new ResolvedTableType($type, $typeExtensions, $parent);
    }

    /**
     * @inheritDoc
     */
    public function createResolvedColumnType(
        Column\ColumnTypeInterface $type,
        array $typeExtensions,
        Column\ResolvedColumnTypeInterface $parent = null
    ) {
        return new Column\ResolvedColumnType($type, $typeExtensions, $parent);
    }

    /**
     * @inheritDoc
     */
    public function createResolvedFilterType(
        Filter\FilterTypeInterface $type,
        array $typeExtensions,
        Filter\ResolvedFilterTypeInterface $parent = null
    ) {
        return new Filter\ResolvedFilterType($type, $typeExtensions, $parent);
    }

    /**
     * @inheritDoc
     */
    public function createResolvedActionType(
        Action\ActionTypeInterface $type,
        array $typeExtensions,
        Action\ResolvedActionTypeInterface $parent = null
    ) {
        return new Action\ResolvedActionType($type, $typeExtensions, $parent);
    }
}
