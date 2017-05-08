<?php

namespace Ekyna\Component\Table\Bridge\Doctrine\ORM\Source;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Ekyna\Component\Table\Context\ContextInterface;
use Ekyna\Component\Table\Exception;
use Ekyna\Component\Table\Source\AbstractAdapter;
use Ekyna\Component\Table\Source\SourceInterface;
use Ekyna\Component\Table\TableInterface;
use Pagerfanta\Adapter\DoctrineORMAdapter;

/**
 * Class EntityAdapter
 * @package Ekyna\Component\Table\Bridge\Doctrine\ORM\Source
 * @author  Etienne Dauvergne <contact@ekyna.com>
 *
 * @method EntitySource getSource()
 */
class EntityAdapter extends AbstractAdapter
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * @var ClassMetadata
     */
    private $metadata;

    /**
     * @var \Doctrine\ORM\QueryBuilder
     */
    private $queryBuilder;

    /**
     * @var string
     */
    private $alias;

    /**
     * @var array
     */
    private $paths;

    /**
     * @var array
     */
    private $aliases;


    /**
     * Constructor.
     *
     * @param TableInterface         $table
     * @param EntityManagerInterface $manager
     */
    public function __construct(TableInterface $table, EntityManagerInterface $manager)
    {
        parent::__construct($table);

        $this->manager = $manager;
    }

    /**
     * Returns the manager.
     *
     * @return EntityManagerInterface
     */
    public function getManager()
    {
        return $this->manager;
    }

    /**
     * Returns the queryBuilder.
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getQueryBuilder()
    {
        return $this->queryBuilder;
    }

    /**
     * Converts the property path to a query builder path and configures the necessary joins.
     *
     * @param string $propertyPath
     *
     * @return string
     */
    public function getQueryBuilderPath($propertyPath)
    {
        if (false === strpos($propertyPath, '.')) {
            return $this->alias . '.' . $propertyPath;
        }

        if (isset($this->paths[$propertyPath])) {
            return $this->paths[$propertyPath];
        }

        $paths = explode('.', $propertyPath);
        $property = array_pop($paths);

        $path = implode('.', array_slice($paths, 0, $i = count($paths)));

        if (!isset($this->aliases[$path])) {
            // Skip already defined aliases
            do {
                $i--;
                $path = implode('.', array_slice($paths, 0, $i));
                if (isset($this->aliases[$path])) {
                    break;
                }
            } while ($i > 0);

            do {
                // TODO Join may have been made by the source's query builder initializer : retrieve the configured alias
                //$this->queryBuilder->getDQLPart('join');

                $alias = chr(98 + count($this->aliases));

                $this->queryBuilder->leftJoin(($i == 0 ? $this->alias : $this->aliases[$path]) . '.' . $paths[$i], $alias);

                // Add select if manyToOne and lvl = 1 (ex: product.brand => b)
                if ($i == 0 && $this->getClassMetadata()->isSingleValuedAssociation($paths[$i])) {
                    $this->queryBuilder->addSelect($alias);
                }

                $i++;
                $path = implode('.', array_slice($paths, 0, $i));
                $this->aliases[$path] = $alias;
            } while ($i < count($paths));
        }

        return $this->paths[$propertyPath] = $this->aliases[$path] . '.' . $property;
    }

    /**
     * @inheritDoc
     */
    protected function preInitialize(ContextInterface $context)
    {
        // Create the initial query builder
        $this->queryBuilder = $this->manager->createQueryBuilder();
        $this
            ->queryBuilder
            ->select($this->alias)
            ->from($this->getSource()->getClass(), $this->alias, $this->alias . '.id');

        // Apply custom query builder initializer
        if (null !== $initializer = $this->getSource()->getQueryBuilderInitializer()) {
            $initializer($this->queryBuilder, $this->alias);
        }
    }

    /**
     * @inheritDoc
     */
    protected function initializeSorting(ContextInterface $context)
    {
        if (!$context->getActiveSort() && !empty($sorts = $this->table->getConfig()->getDefaultSorts())) {
            foreach ($sorts as $propertyPath => $direction) {
                $path = $this->getQueryBuilderPath($propertyPath);
                $this
                    ->queryBuilder
                    ->addOrderBy($path, $direction);
            }

            return;
        }

        parent::initializeSorting($context);
    }

    /**
     * @inheritDoc
     */
    protected function initializeSelection(ContextInterface $context)
    {
        $this->queryBuilder->andWhere(
            $this->queryBuilder->expr()->in(
                $this->alias . '.id',
                $context->getSelectedIdentifiers()
            )
        );
    }

    /**
     * @inheritDoc
     */
    protected function getSelectedRows()
    {
        $results = $this->queryBuilder->getQuery()->getResult();

        $rows = [];
        foreach ($results as $id => $result) {
            $rows[] = $this->createRow($id, $result);
        }

        return $rows;
    }

    /**
     * @inheritDoc
     */
    protected function getPagerAdapter()
    {
        return new DoctrineORMAdapter($this->queryBuilder);
    }

    /**
     * @inheritdoc
     */
    protected function reset()
    {
        parent::reset();

        $this->queryBuilder = null;
        $this->alias = 'a';
        $this->paths = [];
        $this->aliases = [];
    }

    /**
     * @inheritDoc
     */
    protected function validateSource(SourceInterface $source)
    {
        if (!$source instanceof EntitySource) {
            throw new Exception\InvalidArgumentException($source, EntitySource::class);
        }
    }

    /**
     * Returns the class metadata.
     *
     * @return ClassMetadata
     */
    private function getClassMetadata()
    {
        if (null !== $this->metadata) {
            return $this->metadata;
        }

        return $this->metadata = $this->manager->getClassMetadata($this->getSource()->getClass());
    }
}
