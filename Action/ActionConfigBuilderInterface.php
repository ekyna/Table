<?php

declare(strict_types=1);

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
     * @return $this|ActionConfigBuilderInterface
     */
    public function setType(ResolvedActionTypeInterface $type): ActionConfigBuilderInterface;

    /**
     * Sets the action label.
     *
     * @param string $label
     *
     * @return $this|ActionConfigBuilderInterface
     */
    public function setLabel(string $label): ActionConfigBuilderInterface;

    /**
     * Builds and returns the action configuration.
     *
     * @return ActionConfigInterface
     */
    public function getActionConfig(): ActionConfigInterface;
}
