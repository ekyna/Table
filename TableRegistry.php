<?php

namespace Ekyna\Component\Table;

use Ekyna\Component\Table\Exception\ExceptionInterface;
use Ekyna\Component\Table\Exception\UnexpectedTypeException;
use Ekyna\Component\Table\Exception\InvalidArgumentException;

/**
 * Class TableRegistry
 * @package Ekyna\Component\Table
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class TableRegistry implements TableRegistryInterface
{
    /**
     * Extensions
     * @var TableExtensionInterface[] An array of TableExtensionInterface
     */
    private $extensions = array();

    /**
     * @var array
     */
    private $tableTypes = array();

    /**
     * @var array
     */
    private $columnTypes = array();

    /**
     * @var array
     */
    private $filterTypes = array();

    /**
     * Constructor
     *
     * @param TableExtensionInterface[] $extensions An array of TableExtensionInterface
     */
    public function __construct(array $extensions = array())
    {
        $this->addExtensions($extensions);
    }

    /**
     * Adds extensions
     * 
     * @param TableExtensionInterface[] $extensions An array of TableExtensionInterface
     *
     * @throws UnexpectedTypeException if any extension does not implement TableExtensionInterface
     */
    public function addExtensions($extensions)
    {
        if(!is_array($extensions)) {
            $extensions = array($extensions);
        }
        foreach ($extensions as $extension) {
            if (!$extension instanceof TableExtensionInterface) {
                throw new UnexpectedTypeException($extension, 'Ekyna\Component\Table\TableExtensionInterface');
            }
            $this->extensions[] = $extension;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getTableType($name)
    {
        if (!is_string($name)) {
            throw new UnexpectedTypeException($name, 'string');
        }

        if (!array_key_exists($name, $this->tableTypes)) {
            /** @var TableTypeInterface $type */
            $type = null;

            foreach ($this->extensions as $extension) {
                /* @var TableExtensionInterface $extension */
                if ($extension->hasTableType($name)) {
                    $type = $extension->getTableType($name);
                    break;
                }
            }

            if (!$type) {
                throw new InvalidArgumentException(sprintf('Could not load table type "%s"', $name));
            }

            $this->tableTypes[$type->getName()] = $type;
            //$this->resolveAndAddType($type);
        }

        return $this->tableTypes[$name];
    }

    /**
     * {@inheritdoc}
     */
    public function hasTableType($name)
    {
        if (array_key_exists($name, $this->tableTypes)) {
            return true;
        }
    
        try {
            $this->getTableType($name);
        } catch (ExceptionInterface $e) {
            return false;
        }
    
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getColumnType($name)
    {
        if (!is_string($name)) {
            throw new UnexpectedTypeException($name, 'string');
        }

        if (!array_key_exists($name, $this->columnTypes)) {
            /** @var ColumnTypeInterface $type */
            $type = null;

            foreach ($this->extensions as $extension) {
                /* @var TableExtensionInterface $extension */
                if ($extension->hasColumnType($name)) {
                    $type = $extension->getColumnType($name);
                    break;
                }
            }

            if (!$type) {
                throw new InvalidArgumentException(sprintf('Could not load column type "%s"', $name));
            }

            $this->columnTypes[$type->getName()] = $type;
            //$this->resolveAndAddType($type);
        }

        return $this->columnTypes[$name];
    }

    /**
     * {@inheritdoc}
     */
    public function hasColumnType($name)
    {
        if (array_key_exists($name, $this->columnTypes)) {
            return true;
        }

        try {
            $this->getColumnType($name);
        } catch (ExceptionInterface $e) {
            return false;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getFilterType($name)
    {
        if (!is_string($name)) {
            throw new UnexpectedTypeException($name, 'string');
        }

        if (!array_key_exists($name, $this->filterTypes)) {
            /** @var FilterTypeInterface $type */
            $type = null;

            foreach ($this->extensions as $extension) {
                /* @var TableExtensionInterface $extension */
                if ($extension->hasFilterType($name)) {
                    $type = $extension->getFilterType($name);
                    break;
                }
            }

            if (!$type) {
                throw new InvalidArgumentException(sprintf('Could not load filter type "%s"', $name));
            }

            $this->filterTypes[$type->getName()] = $type;
            //$this->resolveAndAddType($type);
        }

        return $this->filterTypes[$name];
    }

    /**
     * {@inheritdoc}
     */
    public function hasFilterType($name)
    {
        if (array_key_exists($name, $this->filterTypes)) {
            return true;
        }

        try {
            $this->getFilterType($name);
        } catch (ExceptionInterface $e) {
            return false;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getExtensions()
    {
    	return $this->extensions;
    }
}