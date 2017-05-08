<?php

namespace Ekyna\Component\Table\Action;

use Ekyna\Component\Table\Extension\Core\Type\Action\ActionType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AbstractActionType
 * @package Ekyna\Component\Table\Action
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
abstract class AbstractActionType implements ActionTypeInterface
{
    /**
     * @inheritDoc
     */
    public function buildAction(ActionBuilderInterface $action, array $options)
    {
    }

    /**
     * @inheritDoc
     */
    public function execute(ActionInterface $action, array $options)
    {
    }

    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
    }

    /**
     * @inheritDoc
     */
    public function getParent()
    {
        return ActionType::class;
    }
}
