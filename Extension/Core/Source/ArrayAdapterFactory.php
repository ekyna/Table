<?php

declare(strict_types=1);

namespace Ekyna\Component\Table\Extension\Core\Source;

use Ekyna\Component\Table\Source\AdapterFactoryInterface;
use Ekyna\Component\Table\Source\AdapterInterface;
use Ekyna\Component\Table\TableInterface;

/**
 * Class ArrayAdapterFactory
 * @package Ekyna\Component\Table\Extension\Core\Source
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class ArrayAdapterFactory implements AdapterFactoryInterface
{
    /**
     * @inheritDoc
     */
    public function createAdapter(TableInterface $table): AdapterInterface
    {
        return new ArrayAdapter($table);
    }
}
