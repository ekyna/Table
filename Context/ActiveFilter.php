<?php

declare(strict_types=1);

namespace Ekyna\Component\Table\Context;

use Ekyna\Component\Table\Exception\InvalidArgumentException;
use Ekyna\Component\Table\Util\FilterOperator;

use function count;
use function serialize;
use function unserialize;

/**
 * Class ActiveFilter
 * @package Ekyna\Component\Table\Context
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
final class ActiveFilter
{
    private string $id;
    private string $filterName;
    private int    $operator = FilterOperator::EQUAL;
    private mixed  $value    = null;


    /**
     * Constructor.
     *
     * @param string $id
     * @param string $filterName
     */
    public function __construct(string $id, string $filterName)
    {
        $this->id = $id;
        $this->filterName = $filterName;
    }

    /**
     * Returns the active filter id.
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Returns the filter name.
     *
     * @return string
     */
    public function getFilterName(): string
    {
        return $this->filterName;
    }

    /**
     * Returns the operator.
     *
     * @return int
     */
    public function getOperator(): int
    {
        return $this->operator;
    }

    /**
     * Sets the operator.
     *
     * @param int $operator
     */
    public function setOperator(int $operator): void
    {
        $this->operator = $operator;
    }

    /**
     * Returns the value.
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Sets the value.
     *
     * @param mixed $value
     */
    public function setValue($value): void
    {
        // TODO Validates value as scalar or array of scalar
        $this->value = $value;
    }

    /**
     * Returns the array representation of the active filter.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            $this->id,
            $this->filterName,
            $this->operator,
            serialize($this->value),
        ];
    }

    /**
     * Creates the active filter from the given data array.
     *
     * @param array $data
     *
     * @return ActiveFilter
     */
    public static function createFromArray(array $data): ActiveFilter
    {
        if (4 != count($data)) {
            throw new InvalidArgumentException('Expected data as a 4 length array.');
        }

        $filter = new self($data[0], $data[1]);
        $filter->setOperator($data[2]);
        $filter->setValue(unserialize($data[3]));

        return $filter;
    }
}
