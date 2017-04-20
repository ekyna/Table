<?php

declare(strict_types=1);

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
    public function buildAction(ActionBuilderInterface $builder, array $options): void
    {
    }

    /**
     * @inheritDoc
     */
    public function execute(ActionInterface $action, array $options)
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
    }

    /**
     * @inheritDoc
     */
    public function getParent(): ?string
    {
        return ActionType::class;
    }
}
