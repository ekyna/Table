<?php

declare(strict_types=1);

namespace Ekyna\Component\Table\Extension\Core\Type\Action;

use Ekyna\Component\Table\Action\AbstractActionType;
use Ekyna\Component\Table\Action\ActionBuilderInterface;
use Ekyna\Component\Table\Action\ActionInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use function ucfirst;

/**
 * Class ActionType
 * @package Ekyna\Component\Table\Extension\Core\Type\Action
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class ActionType extends AbstractActionType
{
    /**
     * @inheritDoc
     */
    public function buildAction(ActionBuilderInterface $builder, array $options): void
    {
        $builder->setLabel($options['label'] ?: ucfirst($builder->getName()));
    }

    /**
     * @inheritDoc
     */
    public function execute(ActionInterface $action, array $options)
    {
        /*
        // Example:
        $table = $action->getTable();
        // The selected row's data
        $data = $table->getSourceAdapter()->getSelection(
            $table->getContext()
        );
        // Do something
        */

        return false;
    }

    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                //'position'   => 0,
                'label'        => null,
                'attr'         => [],
            ])
            //->setAllowedTypes('position', 'int')
            ->setAllowedTypes('label', ['null', 'string'])
            ->setAllowedTypes('attr', 'array');
    }

    /**
     * @inheritDoc
     */
    public function getParent(): ?string
    {
        return null;
    }
}
