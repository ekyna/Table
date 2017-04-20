<?php

declare(strict_types=1);

namespace Ekyna\Component\Table\Bridge\Symfony\DependencyInjection;

use Ekyna\Component\Table\Action\ActionTypeInterface;
use Ekyna\Component\Table\Column\ColumnTypeInterface;
use Ekyna\Component\Table\Exception\InvalidArgumentException;
use Ekyna\Component\Table\Extension\TableExtensionInterface;
use Ekyna\Component\Table\Filter\FilterTypeInterface;
use Ekyna\Component\Table\Source\AdapterFactoryInterface;
use Ekyna\Component\Table\TableTypeInterface;
use Psr\Container\ContainerInterface;

use function array_search;
use function class_exists;
use function in_array;

/**
 * Class DependencyInjectionExtension
 * @package Ekyna\Component\Table\Bridge\Symfony\DependencyInjection
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class DependencyInjectionExtension implements TableExtensionInterface
{
    /**
     * @var ContainerInterface
     */
    private ContainerInterface $container;

    /** @var string[] */
    private array $tableTypeServiceIds;
    /** @var string[] */
    private array $tableTypeExtensionServiceIds;

    /** @var string[] */
    private array $columnTypeServiceIds;
    /** @var string[] */
    private array $columnTypeExtensionServiceIds;

    /** @var string[] */
    private array $filterTypeServiceIds;
    /** @var string[] */
    private array $filterTypeExtensionServiceIds;

    /** @var string[] */
    private array $actionTypeServiceIds;
    /** @var string[] */
    private array $actionTypeExtensionServiceIds;

    /** @var string[] */
    private array $adapterFactoryServiceIds;


    /**
     * Constructor.
     *
     * @param ContainerInterface $container
     * @param string[]           $tableTypeServiceIds
     * @param string[]           $tableTypeExtensionServiceIds
     * @param string[]           $columnTypeServiceIds
     * @param string[]           $columnTypeExtensionServiceIds
     * @param string[]           $filterTypeServiceIds
     * @param string[]           $filterTypeExtensionServiceIds
     * @param string[]           $actionTypeServiceIds
     * @param string[]           $actionTypeExtensionServiceIds
     * @param string[]           $adapterFactoryServiceIds
     */
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
     * @inheritDoc
     */
    public function getTableType(string $name): TableTypeInterface
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->getService($name, $this->tableTypeServiceIds);
    }

    /**
     * @inheritDoc
     */
    public function hasTableType(string $name): bool
    {
        return $this->hasService($name, $this->tableTypeServiceIds);
    }

    /**
     * @inheritDoc
     */
    public function getTableTypeExtensions(string $name): array
    {
        return $this->getExtensions($name, $this->tableTypeServiceIds, $this->tableTypeExtensionServiceIds);
    }

    /**
     * @inheritDoc
     */
    public function hasTableTypeExtensions(string $name): bool
    {
        return $this->hasExtensions($name, $this->tableTypeServiceIds, $this->tableTypeExtensionServiceIds);
    }

    /**
     * @inheritDoc
     */
    public function getColumnType(string $name): ColumnTypeInterface
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->getService($name, $this->columnTypeServiceIds);
    }

    /**
     * @inheritDoc
     */
    public function hasColumnType(string $name): bool
    {
        return $this->hasService($name, $this->columnTypeServiceIds);
    }

    /**
     * @inheritDoc
     */
    public function getColumnTypeExtensions(string $name): array
    {
        return $this->getExtensions($name, $this->columnTypeServiceIds, $this->columnTypeExtensionServiceIds);
    }

    /**
     * @inheritDoc
     */
    public function hasColumnTypeExtensions(string $name): bool
    {
        return $this->hasExtensions($name, $this->columnTypeServiceIds, $this->columnTypeExtensionServiceIds);
    }

    /**
     * @inheritDoc
     */
    public function getFilterType(string $name): FilterTypeInterface
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->getService($name, $this->filterTypeServiceIds);
    }

    /**
     * @inheritDoc
     */
    public function hasFilterType(string $name): bool
    {
        return $this->hasService($name, $this->filterTypeServiceIds);
    }

    /**
     * @inheritDoc
     */
    public function getFilterTypeExtensions(string $name): array
    {
        return $this->getExtensions($name, $this->filterTypeServiceIds, $this->filterTypeExtensionServiceIds);
    }

    /**
     * @inheritDoc
     */
    public function hasFilterTypeExtensions(string $name): bool
    {
        return $this->hasExtensions($name, $this->filterTypeServiceIds, $this->filterTypeExtensionServiceIds);
    }

    /**
     * @inheritDoc
     */
    public function getActionType(string $name): ActionTypeInterface
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->getService($name, $this->actionTypeServiceIds);
    }

    /**
     * @inheritDoc
     */
    public function hasActionType(string $name): bool
    {
        return $this->hasService($name, $this->actionTypeServiceIds);
    }

    /**
     * @inheritDoc
     */
    public function getActionTypeExtensions(string $name): array
    {
        return $this->getExtensions($name, $this->actionTypeServiceIds, $this->actionTypeExtensionServiceIds);
    }

    /**
     * @inheritDoc
     */
    public function hasActionTypeExtensions(string $name): bool
    {
        return $this->hasExtensions($name, $this->actionTypeServiceIds, $this->actionTypeExtensionServiceIds);
    }

    /**
     * @inheritDoc
     */
    public function getAdapterFactory(string $name): AdapterFactoryInterface
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->getService($name, $this->adapterFactoryServiceIds);
    }

    /**
     * @inheritDoc
     */
    public function hasAdapterFactory(string $name): bool
    {
        return $this->hasService($name, $this->adapterFactoryServiceIds);
    }

    /**
     * Returns the type.
     *
     * @param string $name
     * @param array  $serviceIds
     *
     * @return object
     */
    private function getService(string $name, array $serviceIds): object
    {
        // By class
        if (isset($serviceIds[$name])) {
            if ($this->container->has($serviceIds[$name])) {
                return $this->container->get($serviceIds[$name]);
            }
        }

        // By name
        $id = array_search($name, $serviceIds, true);
        if ((false !== $id) && $this->container->has($id)) {
            return $this->container->get($id);
        }

        throw new InvalidArgumentException(sprintf(
            'The table type "%s" is not registered in the service container.',
            $name
        ));
    }

    /**
     * Returns whether the service exists.
     *
     * @param string $name
     * @param array  $serviceIds
     *
     * @return bool
     */
    private function hasService(string $name, array $serviceIds): bool
    {
        return isset($serviceIds[$name]) || in_array($name, $serviceIds, true);
    }

    /**
     * Returns the service class.
     *
     * @param string $name
     * @param array  $services
     *
     * @return string|null
     */
    private function getServiceClass(string $name, array $services): ?string
    {
        if (class_exists($name)) {
            return $name;
        }

        if (false !== $id = array_search($name, $services, true)) {
            return $id;
        }

        return null;
    }

    /**
     * Returns the extensions for the given type.
     *
     * @param string $name       The type
     * @param array  $types      The types services map
     * @param array  $extensions The types extensions
     *
     * @return array
     */
    private function getExtensions(string $name, array $types, array $extensions): array
    {
        $class = $this->getServiceClass($name, $types);

        if (!isset($extensions[$class]) || empty(isset($extensions[$class]))) {
            return [];
        }

        $result = [];

        foreach ($extensions[$class] as $serviceId) {
            $result[] = $extension = $this->container->get($serviceId);

            // validate result of getExtendedType() to ensure it is consistent with the service definition
            if (in_array($name, $extension->getExtendedTypes(), true)) {
                continue;
            }

            throw new InvalidArgumentException(sprintf(
                'The extended type specified for the service "%s" does not match ' .
                'the actual extended type. Expected "%s", given "%s".',
                $serviceId,
                $name,
                $extension->getExtendedType()
            ));
        }

        return $result;
    }

    /**
     * Returns whether extensions are registered for the given type.
     *
     * @param string $name       The type
     * @param array  $types      The types services map
     * @param array  $extensions The types extensions
     *
     * @return bool
     */
    private function hasExtensions(string $name, array $types, array $extensions): bool
    {
        $class = $this->getServiceClass($name, $types);

        return isset($extensions[$class]) && !empty($extensions[$class]);
    }
}
