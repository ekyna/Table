<?php

declare(strict_types=1);

namespace Ekyna\Component\Table;

/**
 * Class ResolvedTypeFactory
 * @package Ekyna\Component\Table
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class ResolvedTypeFactory implements ResolvedTypeFactoryInterface
{
    /**
     * @inheritDoc
     */
    public function createResolvedTableType(
        TableTypeInterface $type,
        array $typeExtensions,
        ResolvedTableTypeInterface $parent = null
    ): ResolvedTableTypeInterface {
        return new ResolvedTableType($type, $typeExtensions, $parent);
    }

    /**
     * @inheritDoc
     */
    public function createResolvedColumnType(
        Column\ColumnTypeInterface $type,
        array $typeExtensions,
        Column\ResolvedColumnTypeInterface $parent = null
    ): Column\ResolvedColumnTypeInterface {
        return new Column\ResolvedColumnType($type, $typeExtensions, $parent);
    }

    /**
     * @inheritDoc
     */
    public function createResolvedFilterType(
        Filter\FilterTypeInterface $type,
        array $typeExtensions,
        Filter\ResolvedFilterTypeInterface $parent = null
    ): Filter\ResolvedFilterTypeInterface {
        return new Filter\ResolvedFilterType($type, $typeExtensions, $parent);
    }

    /**
     * @inheritDoc
     */
    public function createResolvedActionType(
        Action\ActionTypeInterface $type,
        array $typeExtensions,
        Action\ResolvedActionTypeInterface $parent = null
    ): Action\ResolvedActionTypeInterface {
        return new Action\ResolvedActionType($type, $typeExtensions, $parent);
    }
}
