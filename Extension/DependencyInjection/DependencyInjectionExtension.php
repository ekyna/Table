<?php

namespace Ekyna\Component\Table\Extension\DependencyInjection;

use Ekyna\Component\Table\TableExtensionInterface;
use Ekyna\Component\Table\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class DependencyInjectionExtension
 * @package Ekyna\Component\Table\Extension\DependencyInjection
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class DependencyInjectionExtension implements TableExtensionInterface
{
    private $container;
    private $tableTypeServiceIds;
    private $columnTypeServiceIds;
    private $filterTypeServiceIds;

    public function __construct(
        ContainerInterface $container,
        array $tableTypeServiceIds, 
        array $columnTypeServiceIds,
        array $filterTypeServiceIds
    ) {
        $this->container = $container;
        $this->tableTypeServiceIds = $tableTypeServiceIds;
        $this->columnTypeServiceIds = $columnTypeServiceIds;
        $this->filterTypeServiceIds = $filterTypeServiceIds;
    }

    /**
     * {@inheritdoc}
     */
    public function getTableType($name)
    {
        if (!$this->hasTableType($name)) {
            throw new InvalidArgumentException(sprintf('The table type "%s" is not registered with the service container.', $name));
        }

        $type = $this->container->get($this->tableTypeServiceIds[$name]);

        if ($type->getName() !== $name) {
            throw new InvalidArgumentException(
                sprintf('The table type name specified for the service "%s" does not match the actual name. Expected "%s", given "%s"',
                    $this->tableTypeServiceIds[$name],
                    $name,
                    $type->getName()
                ));
        }

        return $type;
    }

    /**
     * {@inheritdoc}
     */
    public function hasTableType($name)
    {
        return array_key_exists($name, $this->tableTypeServiceIds);
    }

    /**
     * {@inheritdoc}
     */
    public function getColumnType($name)
    {
        if (!$this->hasColumnType($name)) {
            throw new InvalidArgumentException(sprintf('The column field type "%s" is not registered with the service container.', $name));
        }

        $type = $this->container->get($this->columnTypeServiceIds[$name]);

        if ($type->getName() !== $name) {
            throw new InvalidArgumentException(
                sprintf('The column type name specified for the service "%s" does not match the actual name. Expected "%s", given "%s"',
                    $this->columnTypeServiceIds[$name],
                    $name,
                    $type->getName()
                ));
        }

        return $type;
    }

    /**
     * {@inheritdoc}
     */
    public function hasColumnType($name)
    {
        return array_key_exists($name, $this->columnTypeServiceIds);
    }

    /**
     * {@inheritdoc}
     */
    public function getFilterType($name)
    {
        if (!$this->hasFilterType($name)) {
            throw new InvalidArgumentException(sprintf('The filter field type "%s" is not registered with the service container.', $name));
        }

        $type = $this->container->get($this->filterTypeServiceIds[$name]);

        if ($type->getName() !== $name) {
            throw new InvalidArgumentException(
                sprintf('The filter type name specified for the service "%s" does not match the actual name. Expected "%s", given "%s"',
                    $this->filterTypeServiceIds[$name],
                    $name,
                    $type->getName()
                ));
        }

        return $type;
    }

    /**
     * {@inheritdoc}
     */
    public function hasFilterType($name)
    {
        return array_key_exists($name, $this->filterTypeServiceIds);
    }
}
