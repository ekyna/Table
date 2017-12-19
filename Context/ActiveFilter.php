<?php

namespace Ekyna\Component\Table\Context;

use Ekyna\Component\Table\Exception\InvalidArgumentException;

/**
 * Class ActiveFilter
 * @package Ekyna\Component\Table\Context
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class ActiveFilter
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $filterName;

    /**
     * @var string
     */
    private $operator;

    /**
     * @var mixed
     */
    private $value;


    /**
     * Constructor.
     *
     * @param string $id
     * @param string $filterName
     */
    public function __construct($id, $filterName)
    {
        $this->id = (string)$id;
        $this->filterName = (string)$filterName;
    }

    /**
     * Returns the active filter id.
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the filter name.
     *
     * @return string
     */
    public function getFilterName()
    {
        return $this->filterName;
    }

    /**
     * Returns the operator.
     *
     * @return string
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * Sets the operator.
     *
     * @param string $operator
     */
    public function setOperator($operator)
    {
        $this->operator = (int)$operator;
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
    public function setValue($value)
    {
        // TODO Validates value as scalar or array of scalar
        $this->value = $value;
    }

    /**
     * Returns the array representation of the active filter.
     *
     * @return array
     */
    public function toArray()
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
    static public function createFromArray(array $data)
    {
        if (4 != count($data)) {
            throw new InvalidArgumentException("Expected data as a 4 length array.");
        }

        $filter = new static($data[0], $data[1]);
        $filter->setOperator($data[2]);
        $filter->setValue(unserialize($data[3]));

        return $filter;
    }
}
