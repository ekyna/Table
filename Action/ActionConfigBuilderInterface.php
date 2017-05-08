<?php

namespace Ekyna\Component\Table\Action;

/**
 * Interface ActionConfigBuilderInterface
 * @package Ekyna\Component\Table\Action
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
interface ActionConfigBuilderInterface extends ActionConfigInterface
{
    /**
     * Sets the action type.
     *
     * @param ResolvedActionTypeInterface $type
     *
     * @return $this
     */
    public function setType(ResolvedActionTypeInterface $type);

    /**
     * Builds and returns the action configuration.
     *
     * @return ActionConfigInterface
     */
    public function getActionConfig();
}
