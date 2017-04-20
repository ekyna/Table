<?php

declare(strict_types=1);

namespace Ekyna\Component\Table\Http\Handler;

use Ekyna\Component\Table\TableError;
use Ekyna\Component\Table\TableInterface;

/**
 * Class ProfileHandler
 * @package Ekyna\Component\Table\Http\Handler
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
final class ProfileHandler implements HandlerInterface
{
    /**
     * @inheritDoc
     */
    public function execute(TableInterface $table, object $request = null): ?object
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
                $table->addError(new TableError('error.profile_required', [], 'EkynaTable'));

                return null;
            }

            $table->getContext()->fromArray($storage->get($key)->getData());

            return null;
        }

        if ($parameters->isProfileEditClicked()) {
            if (empty($key = $parameters->getProfileChoiceValue())) {
                $table->addError(new TableError('error.profile_required', [], 'EkynaTable'));

                return null;
            }

            // TODO Not found error
            $profile = $storage->get($key);
            $profile->setData($table->getContext()->toArray());
            $storage->save($profile);

            return null;
        }

        if ($parameters->isProfileRemoveClicked()) {
            if (empty($key = $parameters->getProfileChoiceValue())) {
                $table->addError(new TableError('error.profile_required', [], 'EkynaTable'));

                return null;
            }

            $storage->remove($storage->get($key));

            return null;
        }

        if ($parameters->isProfileCreateClicked()) {
            if (empty($name = $parameters->getProfileNameValue())) {
                $table->addError(new TableError('error.name_required', [], 'EkynaTable'));

                return null;
            }

            $storage->create($table, $name);
        }

        return null;
    }
}
