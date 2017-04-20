<?php

declare(strict_types=1);

namespace Ekyna\Component\Table\Extension;

use Ekyna\Component\Table\Action\ActionBuilderInterface;
use Ekyna\Component\Table\Action\ActionInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AbstractActionTypeExtension
 * @package Ekyna\Component\Table\Extension
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
abstract class AbstractActionTypeExtension implements ActionTypeExtensionInterface
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
    public function execute(ActionInterface $action, array $options): bool
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
    }
}
