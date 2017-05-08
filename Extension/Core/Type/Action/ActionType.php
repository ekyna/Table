<?php

namespace Ekyna\Component\Table\Extension\Core\Type\Action;

use Ekyna\Component\Table\Action\AbstractActionType;
use Ekyna\Component\Table\Action\ActionInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                //'position'           => 0,
                'label'              => null,
                'translation_domain' => null,
                'attr'               => [],
            ])
            //->setAllowedTypes('position', 'int')
            ->setAllowedTypes('label', ['null', 'string'])
            ->setAllowedTypes('translation_domain', ['null', 'string'])
            ->setAllowedTypes('attr', 'array');
    }

    /**
     * @inheritDoc
     */
    public function getParent()
    {
        return null;
    }
}
