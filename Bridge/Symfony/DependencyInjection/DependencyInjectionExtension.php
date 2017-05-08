<?php

namespace Ekyna\Component\Table\Bridge\Symfony\DependencyInjection;

use Ekyna\Component\Table\Extension\TableExtensionInterface;
use Ekyna\Component\Table\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class DependencyInjectionExtension
 * @package Ekyna\Component\Table\Bridge\Symfony\DependencyInjection
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class DependencyInjectionExtension implements TableExtensionInterface
{
    private $container;

    private $tableTypeServiceIds;
    private $tableTypeExtensionServiceIds;

    private $columnTypeServiceIds;
    private $columnTypeExtensionServiceIds;

    private $filterTypeServiceIds;
    private $filterTypeExtensionServiceIds;

    private $actionTypeServiceIds;
    private $actionTypeExtensionServiceIds;

    private $adapterFactoryServiceIds;

    public function __construct(
        ContainerInterface $container,
        array $tableTypeServiceIds,
        array $tableTypeExtensionServiceIds,
        array $columnTypeServiceIds,
        array $columnTypeExtensionServiceIds,
        array $filterTypeServiceIds,
        array $filterTypeExtensionServiceIds,
        array $actionTypeServiceIds,
        array $actionTypeExtensionServiceIds,
        array $adapterFactoryServiceIds
    ) {
        $this->container = $container;
        $this->tableTypeServiceIds = $tableTypeServiceIds;
        $this->tableTypeExtensionServiceIds = $tableTypeExtensionServiceIds;
        $this->columnTypeServiceIds = $columnTypeServiceIds;
        $this->columnTypeExtensionServiceIds = $columnTypeExtensionServiceIds;
        $this->filterTypeServiceIds = $filterTypeServiceIds;
        $this->filterTypeExtensionServiceIds = $filterTypeExtensionServiceIds;
        $this->actionTypeServiceIds = $actionTypeServiceIds;
        $this->actionTypeExtensionServiceIds = $actionTypeExtensionServiceIds;
        $this->adapterFactoryServiceIds = $adapterFactoryServiceIds;
    }

    /**
     * @inheritdoc
     */
    public function getTableType($name)
    {
        if (!isset($this->tableTypeServiceIds[$name])) {
            throw new InvalidArgumentException(sprintf(
                'The table type "%s" is not registered with the service container.',
                $name
            ));
        }

        return $this->container->get($this->tableTypeServiceIds[$name]);
    }

    /**
     * @inheritdoc
     */
    public function hasTableType($name)
    {
        return isset($this->tableTypeServiceIds[$name]);
    }

    /**
     * @inheritDoc
     */
    public function getTableTypeExtensions($name)
    {
        $extensions = array();

        if (isset($this->tableTypeExtensionServiceIds[$name])) {
            foreach ($this->tableTypeExtensionServiceIds[$name] as $serviceId) {
                $extensions[] = $extension = $this->container->get($serviceId);

                // validate result of getExtendedType() to ensure it is consistent with the service definition
                if ($extension->getExtendedType() !== $name) {
                    throw new InvalidArgumentException(
                        sprintf(
                            'The extended type specified for the service "%s" does not match '.
                            'the actual extended type. Expected "%s", given "%s".',
                            $serviceId,
                            $name,
                            $extension->getExtendedType()
                        )
                    );
                }
            }
        }

        return $extensions;
    }

    /**
     * @inheritDoc
     */
    public function hasTableTypeExtensions($name)
    {
        return isset($this->tableTypeExtensionServiceIds[$name]);
    }

    /**
     * @inheritdoc
     */
    public function getColumnType($name)
    {
        if (!isset($this->columnTypeServiceIds[$name])) {
            throw new InvalidArgumentException(sprintf(
                'The column field type "%s" is not registered with the service container.',
                $name
            ));
        }

        return $this->container->get($this->columnTypeServiceIds[$name]);
    }

    /**
     * @inheritdoc
     */
    public function hasColumnType($name)
    {
        return isset($this->columnTypeServiceIds[$name]);
    }

    /**
     * @inheritDoc
     */
    public function getColumnTypeExtensions($name)
    {
        $extensions = array();

        if (isset($this->columnTypeExtensionServiceIds[$name])) {
            foreach ($this->columnTypeExtensionServiceIds[$name] as $serviceId) {
                $extensions[] = $extension = $this->container->get($serviceId);

                // validate result of getExtendedType() to ensure it is consistent with the service definition
                if ($extension->getExtendedType() !== $name) {
                    throw new InvalidArgumentException(
                        sprintf(
                            'The extended type specified for the service "%s" does not match '.
                            'the actual extended type. Expected "%s", given "%s".',
                            $serviceId,
                            $name,
                            $extension->getExtendedType()
                        )
                    );
                }
            }
        }

        return $extensions;
    }

    /**
     * @inheritDoc
     */
    public function hasColumnTypeExtensions($name)
    {
        return isset($this->columnTypeExtensionServiceIds[$name]);
    }

    /**
     * @inheritdoc
     */
    public function getFilterType($name)
    {
        if (!$this->filterTypeServiceIds[$name]) {
            throw new InvalidArgumentException(sprintf(
                'The filter field type "%s" is not registered with the service container.',
                $name
            ));
        }

        return $this->container->get($this->filterTypeServiceIds[$name]);
    }

    /**
     * @inheritdoc
     */
    public function hasFilterType($name)
    {
        return isset($this->filterTypeServiceIds[$name]);
    }

    /**
     * @inheritDoc
     */
    public function getFilterTypeExtensions($name)
    {
        $extensions = array();

        if (isset($this->filterTypeExtensionServiceIds[$name])) {
            foreach ($this->filterTypeExtensionServiceIds[$name] as $serviceId) {
                $extensions[] = $extension = $this->container->get($serviceId);

                // validate result of getExtendedType() to ensure it is consistent with the service definition
                if ($extension->getExtendedType() !== $name) {
                    throw new InvalidArgumentException(
                        sprintf(
                            'The extended type specified for the service "%s" does not match '.
                            'the actual extended type. Expected "%s", given "%s".',
                            $serviceId,
                            $name,
                            $extension->getExtendedType()
                        )
                    );
                }
            }
        }

        return $extensions;
    }

    /**
     * @inheritDoc
     */
    public function hasFilterTypeExtensions($name)
    {
        return isset($this->filterTypeExtensionServiceIds[$name]);
    }

    /**
     * @inheritdoc
     */
    public function getActionType($name)
    {
        if (!$this->actionTypeServiceIds[$name]) {
            throw new InvalidArgumentException(sprintf(
                'The action field type "%s" is not registered with the service container.',
                $name
            ));
        }

        return $this->container->get($this->actionTypeServiceIds[$name]);
    }

    /**
     * @inheritdoc
     */
    public function hasActionType($name)
    {
        return isset($this->actionTypeServiceIds[$name]);
    }

    /**
     * @inheritDoc
     */
    public function getActionTypeExtensions($name)
    {
        $extensions = array();

        if (isset($this->actionTypeExtensionServiceIds[$name])) {
            foreach ($this->actionTypeExtensionServiceIds[$name] as $serviceId) {
                $extensions[] = $extension = $this->container->get($serviceId);

                // validate result of getExtendedType() to ensure it is consistent with the service definition
                if ($extension->getExtendedType() !== $name) {
                    throw new InvalidArgumentException(
                        sprintf(
                            'The extended type specified for the service "%s" does not match '.
                            'the actual extended type. Expected "%s", given "%s".',
                            $serviceId,
                            $name,
                            $extension->getExtendedType()
                        )
                    );
                }
            }
        }

        return $extensions;
    }

    /**
     * @inheritDoc
     */
    public function hasActionTypeExtensions($name)
    {
        return isset($this->actionTypeExtensionServiceIds[$name]);
    }

    /**
     * @inheritdoc
     */
    public function getAdapterFactory($name)
    {
        if (!$this->adapterFactoryServiceIds[$name]) {
            throw new InvalidArgumentException(sprintf(
                'The filter field type "%s" is not registered with the service container.',
                $name
            ));
        }

        return $this->container->get($this->adapterFactoryServiceIds[$name]);
    }

    /**
     * @inheritdoc
     */
    public function hasAdapterFactory($name)
    {
        return isset($this->adapterFactoryServiceIds[$name]);
    }
}
