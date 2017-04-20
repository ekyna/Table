<?php

declare(strict_types=1);

namespace Ekyna\Component\Table\Bridge\Doctrine\ORM\Source;

use Closure;
use Doctrine\ORM\QueryBuilder;
use Ekyna\Component\Table\Exception\InvalidArgumentException;
use Ekyna\Component\Table\Source\ClassSourceInterface;
use ReflectionFunction;

use function class_exists;
use function in_array;
use function is_null;

/**
 * Class EntitySource
 * @package Ekyna\Component\Table\Bridge\Doctrine\ORM\Source
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class EntitySource implements ClassSourceInterface
{
    private string   $class;
    private ?Closure $queryBuilderInitializer = null;


    /**
     * Constructor.
     *
     * @param string $class
     */
    public function __construct(string $class)
    {
        if (!class_exists($class)) {
            throw new InvalidArgumentException(sprintf('Class %s does not exists.', $class));
        }

        $this->class = $class;
    }

    /**
     * @inheritDoc
     */
    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * Returns the queryBuilderInitializer.
     *
     * @return Closure
     */
    public function getQueryBuilderInitializer(): ?Closure
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
     * @param Closure|null $initializer
     *
     * @return EntitySource
     */
    public function setQueryBuilderInitializer(Closure $initializer = null): EntitySource
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
     * @param Closure $initializer
     *
     * @throws InvalidArgumentException
     * @noinspection PhpDocMissingThrowsInspection
     */
    private function validateQueryBuilderInitializer(Closure $initializer)
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $reflection = new ReflectionFunction($initializer);
        $parameters = $reflection->getParameters();

        if (2 !== count($parameters)) {
            throw new InvalidArgumentException('The query builder initializer must have two and only two arguments.');
        }

        $class = $parameters[0]->getClass();
        if (!$class || $class->getName() !== QueryBuilder::class) {
            throw new InvalidArgumentException(sprintf(
                "The query builder initializer's first argument must be type hinted to the %s class.",
                QueryBuilder::class
            ));
        }

        $type = (string)$parameters[1]->getType();
        if (!in_array($type, [null, 'string'], true)) {
            throw new InvalidArgumentException(
                "The query builder initializer's second argument must be type hinted to 'string'."
            );
        }
    }

    /**
     * @inheritDoc
     */
    public static function getFactory(): string
    {
        return EntityAdapterFactory::class;
    }
}
