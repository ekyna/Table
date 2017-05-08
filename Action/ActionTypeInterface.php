<?php

namespace Ekyna\Component\Table\Action;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Interface ActionTypeInterface
 * @package Ekyna\Component\Table\Action
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
interface ActionTypeInterface
{
    /**
     * Builds the action.
     *
     * @param ActionBuilderInterface $action
     * @param array                  $options
     */
    public function buildAction(ActionBuilderInterface $action, array $options);

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
     * Configure the options.
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver);

    /**
     * Returns the name of the parent type.
     *
     * @return string|null The name of the parent type if any, null otherwise
     */
    public function getParent();
}
