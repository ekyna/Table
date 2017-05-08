<?php

namespace Ekyna\Component\Table\Context;

use Ekyna\Component\Table\Exception\InvalidArgumentException;

/**
 * Class ActiveSort
 * @package Ekyna\Component\Table\Context
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class ActiveSort
{
    /**
     * @var string
     */
    private $columnName;

    /**
     * @var string
     */
    private $direction;


    /**
     * Constructor.
     *
     * @param string $columnName
     * @param string $direction
     */
    public function __construct($columnName, $direction)
    {
        $this->columnName = (string)$columnName;
        $this->direction = (string)$direction;
    }

    /**
     * Returns the column.
     *
     * @return string
     */
    public function getColumnName()
    {
        return $this->columnName;
    }

    /**
     * Returns the direction.
     *
     * @return string
     */
    public function getDirection()
    {
        return $this->direction;
    }

    /**
     * Returns the array representation of the active filter.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            $this->columnName,
            $this->direction,
        ];
    }

    /**
     * Creates the active sort from the given data array.
     *
     * @param array $data
     *
     * @return ActiveSort
     */
    static public function createFromArray(array $data)
    {
        if (2 != count($data)) {
            throw new InvalidArgumentException("Expected data as a 2 length array.");
        }

        return new static($data[0], $data[1]);
    }
}
