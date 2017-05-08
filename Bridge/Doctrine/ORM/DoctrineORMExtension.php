<?php

namespace Ekyna\Component\Table\Bridge\Doctrine\ORM;

use Doctrine\Common\Persistence\ManagerRegistry;
use Ekyna\Component\Table\Extension\AbstractTableExtension;

/**
 * Class DoctrineORMExtension
 * @package Ekyna\Component\Table\Bridge\Doctrine\ORM
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class DoctrineORMExtension extends AbstractTableExtension
{
    /**
     * @var ManagerRegistry
     */
    private $registry;


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
    protected function loadAdapterFactories()
    {
        return [
            new Source\EntityAdapterFactory($this->registry),
        ];
    }

    /**
     * @inheritDoc
     */
    protected function loadColumnTypes()
    {
        return [
            new Type\Column\EntityType(),
        ];
    }

    /**
     * @inheritDoc
     */
    protected function loadColumnTypeExtensions()
    {
        return [
            new Extension\Column\PropertyTypeExtension(),
        ];
    }

    /**
     * @inheritDoc
     */
    protected function loadFilterTypes()
    {
        return [
            new Type\Filter\EntityType($this->registry),
        ];
    }

    /**
     * @inheritDoc
     */
    protected function loadFilterTypeExtensions()
    {
        return [
            new Extension\Filter\FilterTypeExtension(),
            new Extension\Filter\BooleanTypeExtension(),
            new Extension\Filter\DatetimeFilterTypeExtension(),
        ];
    }
}
