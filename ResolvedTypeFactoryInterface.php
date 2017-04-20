<?php

declare(strict_types=1);

namespace Ekyna\Component\Table;

/**
 * Interface ResolvedTypeFactoryInterface
 * @package Ekyna\Component\Table
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
interface ResolvedTypeFactoryInterface
{
    /**
     * Resolves a table type.
     *
     * @param TableTypeInterface                      $type
     * @param Extension\TableTypeExtensionInterface[] $typeExtensions
     * @param ResolvedTableTypeInterface|null         $parent
     *
     * @return ResolvedTableTypeInterface
     *
     * @throws Exception\UnexpectedTypeException  if the types parent is not a string
     * @throws Exception\InvalidArgumentException if the types parent can not be retrieved from any extension
     */
    public function createResolvedTableType(
        TableTypeInterface $type,
        array $typeExtensions,
        ResolvedTableTypeInterface $parent = null
    ): ResolvedTableTypeInterface;

    /**
     * Resolves a column type.
     *
     * @param Column\ColumnTypeInterface               $type
     * @param Extension\ColumnTypeExtensionInterface[] $typeExtensions
     * @param Column\ResolvedColumnTypeInterface|null  $parent
     *
     * @return Column\ResolvedColumnTypeInterface
     *
     * @throws Exception\UnexpectedTypeException  if the types parent is not a string
     * @throws Exception\InvalidArgumentException if the types parent can not be retrieved from any extension
     */
    public function createResolvedColumnType(
        Column\ColumnTypeInterface $type,
        array $typeExtensions,
        Column\ResolvedColumnTypeInterface $parent = null
    ): Column\ResolvedColumnTypeInterface;

    /**
     * Resolves a filter type.
     *
     * @param Filter\FilterTypeInterface               $type
     * @param Extension\FilterTypeExtensionInterface[] $typeExtensions
     * @param Filter\ResolvedFilterTypeInterface|null  $parent
     *
     * @return Filter\ResolvedFilterTypeInterface
     *
     * @throws Exception\UnexpectedTypeException  if the types parent is not a string
     * @throws Exception\InvalidArgumentException if the types parent can not be retrieved from any extension
     */
    public function createResolvedFilterType(
        Filter\FilterTypeInterface $type,
        array $typeExtensions,
        Filter\ResolvedFilterTypeInterface $parent = null
    ): Filter\ResolvedFilterTypeInterface;

    /**
     * Resolves an action type.
     *
     * @param Action\ActionTypeInterface               $type
     * @param Extension\ActionTypeExtensionInterface[] $typeExtensions
     * @param Action\ResolvedActionTypeInterface|null  $parent
     *
     * @return Action\ResolvedActionTypeInterface
     *
     * @throws Exception\UnexpectedTypeException  if the types parent is not a string
     * @throws Exception\InvalidArgumentException if the types parent can not be retrieved from any extension
     */
    public function createResolvedActionType(
        Action\ActionTypeInterface $type,
        array $typeExtensions,
        Action\ResolvedActionTypeInterface $parent = null
    ): Action\ResolvedActionTypeInterface;
}
