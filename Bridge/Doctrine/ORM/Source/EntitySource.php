<?php

namespace Ekyna\Component\Table\Bridge\Doctrine\ORM\Source;

use Doctrine\ORM\QueryBuilder;
use Ekyna\Component\Table\Exception\InvalidArgumentException;
use Ekyna\Component\Table\Source\ClassSourceInterface;

/**
 * Class EntitySource
 * @package Ekyna\Component\Table\Bridge\Doctrine\ORM\Source
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class EntitySource implements ClassSourceInterface
{
    /**
     * @var string
     */
    private $class;

    /**
     * @var \Closure
     */
    private $queryBuilderInitializer;


    /**
     * Constructor.
     *
     * @param string $class
     */
    public function __construct($class)
    {
        if (!class_exists($class)) {
            throw new InvalidArgumentException(sprintf("Class %s does not exists.", $class));
        }

        $this->class = $class;
    }

    /**
     * @inheritdoc
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Returns the queryBuilderInitializer.
     *
     * @return \Closure
     */
    public function getQueryBuilderInitializer()
    {
        return $this->queryBuilderInitializer;
    }

    /**
     * Sets the query builder initializer.
     *
     * A closure with the query builder as first argument
     * and the root alias as the second argument:
     *
     * function (QueryBuilder $qb, $alias) {
     *
     * }
     *
     * @param \Closure|null $initializer
     *
     * @return EntitySource
     */
    public function setQueryBuilderInitializer(\Closure $initializer = null)
    {
        if (!is_null($initializer)) {
            $this->validateQueryBuilderInitializer($initializer);
        }

        $this->queryBuilderInitializer = $initializer;

        return $this;
    }

    /**
     * Validates the query builder initializer.
     *
     * @param \Closure $initializer
     *
     * @throws InvalidArgumentException
     */
    private function validateQueryBuilderInitializer(\Closure $initializer)
    {
        $reflection = new \ReflectionFunction($initializer);
        $parameters = $reflection->getParameters();

        if (2 !== count($parameters)) {
            throw new InvalidArgumentException("The query builder initializer must have two and only two arguments.");
        }

        $class = $parameters[0]->getClass();
        if (!$class || $class->getName() !== QueryBuilder::class) {
            throw new InvalidArgumentException(sprintf(
                "The query builder initializer's first argument must be type hinted to the %s class.",
                QueryBuilder::class
            ));
        }

        if (!in_array($parameters[1]->getType(), [null, 'string'], true)) {
            throw new InvalidArgumentException(sprintf(
                "The query builder initializer's second must be type hinted to 'string'.",
                QueryBuilder::class
            ));
        }
    }

    /**
     * @inheritDoc
     */
    static public function getFactory()
    {
        return EntityAdapterFactory::class;
    }
}
