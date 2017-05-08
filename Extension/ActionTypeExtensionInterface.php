<?php

namespace Ekyna\Component\Table\Extension;

use Ekyna\Component\Table\Action\ActionBuilderInterface;
use Ekyna\Component\Table\Action\ActionInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Interface ActionTypeExtensionInterface
 * @package Ekyna\Component\Table\Extension
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
interface ActionTypeExtensionInterface
{
    /**
     * Builds the action.
     *
     * This method is called after the extended type has built the action to
     * further modify it.
     *
     * @see ActionTypeInterface::buildAction()
     *
     * @param ActionBuilderInterface $builder The action builder
     * @param array                  $options The options
     */
    public function buildAction(ActionBuilderInterface $builder, array $options);

    /**
     * Applies the action to the adapter.
     *
     * @param ActionInterface $action
     * @param array           $options
     *
     * @return bool Whether the action has been applied.
     */
    public function execute(ActionInterface $action, array $options);

    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options
     */
    public function configureOptions(OptionsResolver $resolver);

    /**
     * Returns the name of the type being extended.
     *
     * @return string The name of the type being extended
     */
    public function getExtendedType();
}
