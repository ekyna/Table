<?php

declare(strict_types=1);

namespace Ekyna\Component\Table\Action;

use Ekyna\Component\Table\Extension\ActionTypeExtensionInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Interface ResolvedActionTypeInterface
 * @package Ekyna\Component\Table\Action
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
interface ResolvedActionTypeInterface
{
    /**
     * Returns the parent type.
     *
     * @return ResolvedActionTypeInterface|null The parent type or null
     */
    public function getParent(): ?ResolvedActionTypeInterface;

    /**
     * Returns the wrapped action type.
     *
     * @return ActionTypeInterface The wrapped action type
     */
    public function getInnerType(): ActionTypeInterface;

    /**
     * Returns the extensions of the wrapped action type.
     *
     * @return ActionTypeExtensionInterface[] An array of {@link ActionTypeExtensionInterface} instances
     */
    public function getTypeExtensions(): array;

    /**
     * Creates a new action builder for this type.
     *
     * @param string $name    The name for the builder
     * @param array  $options The builder options
     *
     * @return ActionBuilderInterface The created action builder
     */
    public function createBuilder(string $name, array $options = []): ActionBuilderInterface;

    /**
     * Configures a action builder for the type hierarchy.
     *
     * @param ActionBuilderInterface $builder The builder to configure
     * @param array                  $options The options used for the configuration
     */
    public function buildAction(ActionBuilderInterface $builder, array $options): void;

    /**
     * Executes the action.
     *
     * @param ActionInterface $action
     * @param array           $options
     *
     * @return bool|object The response object or whether the action has been executed
     */
    public function execute(ActionInterface $action, array $options);

    /**
     * Returns the configured options resolver used for this type.
     *
     * @return OptionsResolver The options resolver
     */
    public function getOptionsResolver(): OptionsResolver;
}
