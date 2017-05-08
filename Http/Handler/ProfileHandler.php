<?php

namespace Ekyna\Component\Table\Http\Handler;

use Ekyna\Component\Table\TableInterface;

/**
 * Class ProfileHandler
 * @package Ekyna\Component\Table\Http\Handler
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class ProfileHandler implements HandlerInterface
{
    /**
     * @inheritDoc
     */
    public function execute(TableInterface $table, $request)
    {
        if (!$table->getConfig()->isProfileable()) {
            return null;
        }

        if (null === $storage = $table->getConfig()->getProfileStorage()) {
            return null;
        }

        $parameters = $table->getParametersHelper();

        if ($parameters->isProfileLoadClicked()) {
            if (empty($key = $parameters->getProfileChoiceValue())) {
                // TODO error
            }

            $table->getContext()->fromArray($storage->get($key)->getData());

            return;
        }

        if ($parameters->isProfileEditClicked()) {
            if (empty($key = $parameters->getProfileChoiceValue())) {
                // TODO error
            }

            $profile = $storage->get($key);
            $profile->setData($table->getContext()->toArray());
            $storage->save($profile);

            return;
        }

        if ($parameters->isProfileRemoveClicked()) {
            if (empty($key = $parameters->getProfileChoiceValue())) {
                // TODO error
            }

            $storage->remove($storage->get($key));

            return;
        }

        if ($parameters->isProfileCreateClicked()) {
            if (empty($name = $parameters->getProfileNameValue())) {
                // TODO error
            }

            $storage->create($table, $name);
        }
    }
}
