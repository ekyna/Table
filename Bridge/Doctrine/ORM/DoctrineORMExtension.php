<?php

declare(strict_types=1);

namespace Ekyna\Component\Table\Bridge\Doctrine\ORM;

use Doctrine\Persistence\ManagerRegistry;
use Ekyna\Component\Table\Extension\AbstractTableExtension;

/**
 * Class DoctrineORMExtension
 * @package Ekyna\Component\Table\Bridge\Doctrine\ORM
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class DoctrineORMExtension extends AbstractTableExtension
{
    private ManagerRegistry $registry;


    /**
     * Constructor.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * @inheritDoc
     */
    protected function loadAdapterFactories(): array
    {
        return [
            new Source\EntityAdapterFactory($this->registry),
        ];
    }

    /**
     * @inheritDoc
     */
    protected function loadColumnTypes(): array
    {
        return [
            new Type\Column\EntityType(),
        ];
    }

    /**
     * @inheritDoc
     */
    protected function loadColumnTypeExtensions(): array
    {
        return [
            new Extension\Column\PropertyTypeExtension(),
        ];
    }

    /**
     * @inheritDoc
     */
    protected function loadFilterTypes(): array
    {
        return [
            new Type\Filter\EntityType($this->registry),
        ];
    }

    /**
     * @inheritDoc
     */
    protected function loadFilterTypeExtensions(): array
    {
        return [
            new Extension\Filter\FilterTypeExtension(),
            new Extension\Filter\BooleanTypeExtension(),
            new Extension\Filter\DatetimeFilterTypeExtension(),
        ];
    }
}
