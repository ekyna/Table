<?php

declare(strict_types=1);

namespace Ekyna\Component\Table\Action;

/**
 * Interface ActionBuilderInterface
 * @package Ekyna\Component\Table\Action
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
interface ActionBuilderInterface extends ActionConfigBuilderInterface
{
    /**
     * Returns the action.
     *
     * @return ActionInterface
     */
    public function getAction(): ActionInterface;
}
