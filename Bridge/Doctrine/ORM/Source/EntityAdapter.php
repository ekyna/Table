<?php

declare(strict_types=1);

namespace Ekyna\Component\Table\Bridge\Doctrine\ORM\Source;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\QueryBuilder;
use Ekyna\Component\Table\Context\ContextInterface;
use Ekyna\Component\Table\Exception;
use Ekyna\Component\Table\Source\AbstractAdapter;
use Ekyna\Component\Table\Source\SourceInterface;
use Ekyna\Component\Table\TableInterface;
use Pagerfanta\Adapter\AdapterInterface as PagerfantaAdapter;
use Pagerfanta\Doctrine\ORM\QueryAdapter;

use function array_pop;
use function array_slice;
use function chr;
use function count;
use function explode;
use function implode;
use function is_object;
use function strpos;

/**
 * Class EntityAdapter
 * @package Ekyna\Component\Table\Bridge\Doctrine\ORM\Source
 * @author  Etienne Dauvergne <contact@ekyna.com>
 *
 * @method EntitySource getSource()
 */
class EntityAdapter extends AbstractAdapter
{
    private EntityManagerInterface $manager;
    private ?ClassMetadata         $metadata     = null;
    private ?QueryBuilder          $queryBuilder = null;
    private string                 $alias;
    private array                  $paths;
    private array                  $aliases;


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
    public function getManager(): EntityManagerInterface
    {
        return $this->manager;
    }

    /**
     * Returns the queryBuilder.
     *
     * @return QueryBuilder|null
     */
    public function getQueryBuilder(): ?QueryBuilder
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
    public function getQueryBuilderPath(string $propertyPath): string
    {
        if (false === strpos($propertyPath, '.')) {
            return $this->alias.'.'.$propertyPath;
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

                $this->queryBuilder->leftJoin(($i == 0 ? $this->alias : $this->aliases[$path]).'.'.$paths[$i], $alias);

                // Add select if manyToOne and lvl = 1 (ex: product.brand => b)
                if ($i == 0 && $this->getClassMetadata()->isSingleValuedAssociation($paths[$i])) {
                    $this->queryBuilder->addSelect($alias);
                }

                $i++;
                $path = implode('.', array_slice($paths, 0, $i));
                $this->aliases[$path] = $alias;
            } while ($i < count($paths));
        }

        return $this->paths[$propertyPath] = $this->aliases[$path].'.'.$property;
    }

    /**
     * @inheritDoc
     */
    protected function preInitialize(ContextInterface $context): void
    {
        // Create the initial query builder
        $this->queryBuilder = $this->manager->createQueryBuilder();
        $this
            ->queryBuilder
            ->select($this->alias)
            ->from($this->getSource()->getClass(), $this->alias, $this->alias.'.id');

        // Apply custom query builder initializer
        if (null !== $initializer = $this->getSource()->getQueryBuilderInitializer()) {
            $initializer($this->queryBuilder, $this->alias);
        }

        parent::preInitialize($context);
    }

    /**
     * @inheritDoc
     */
    protected function initializeSorting(ContextInterface $context): void
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
    protected function initializeSelection(ContextInterface $context): void
    {
        $this->queryBuilder->andWhere(
            $this->queryBuilder->expr()->in(
                $this->alias.'.id',
                $context->getSelectedIdentifiers()
            )
        );
    }

    /**
     * @inheritDoc
     */
    protected function getSelectedRows(): array
    {
        $results = $this->queryBuilder->getQuery()->getResult();

        $rows = [];
        foreach ($results as $id => $result) {
            if (is_object($result)) {
                $data = $result;
                $extra = [];
            } elseif (is_array($result)) {
                $data = array_shift($result);
                $extra = $result;
            } else {
                throw new Exception\UnexpectedTypeException($result, ['object', 'array']);
            }

            $rows[] = $this->createRow((string)$id, $data, $extra);
        }

        return $rows;
    }

    /**
     * @inheritDoc
     */
    protected function getPagerAdapter(): PagerfantaAdapter
    {
        return new QueryAdapter($this->queryBuilder);
    }

    /**
     * @inheritDoc
     */
    protected function reset(): void
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
    protected function validateSource(SourceInterface $source): void
    {
        if ($source instanceof EntitySource) {
            return;
        }

        throw new Exception\UnexpectedTypeException($source, EntitySource::class);
    }

    /**
     * Returns the class metadata.
     *
     * @return ClassMetadata
     */
    private function getClassMetadata(): ClassMetadata
    {
        if (null !== $this->metadata) {
            return $this->metadata;
        }

        return $this->metadata = $this->manager->getClassMetadata($this->getSource()->getClass());
    }
}
