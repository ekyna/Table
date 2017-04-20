<?php

declare(strict_types=1);

namespace Ekyna\Component\Table\Context;

use Ekyna\Component\Table\Exception\InvalidArgumentException;

use function count;

/**
 * Class ActiveSort
 * @package Ekyna\Component\Table\Context
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
final class ActiveSort
{
    private string $columnName;
    private string $direction;


    /**
     * Constructor.
     *
     * @param string $columnName
     * @param string $direction
     */
    public function __construct(string $columnName, string $direction)
    {
        $this->columnName = $columnName;
        $this->direction = $direction;
    }

    /**
     * Returns the column.
     *
     * @return string
     */
    public function getColumnName(): string
    {
        return $this->columnName;
    }

    /**
     * Returns the direction.
     *
     * @return string
     */
    public function getDirection(): string
    {
        return $this->direction;
    }

    /**
     * Returns the array representation of the active filter.
     *
     * @return array
     */
    public function toArray(): array
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
    public static function createFromArray(array $data): ActiveSort
    {
        if (2 !== count($data)) {
            throw new InvalidArgumentException('Expected data as a 2 length array.');
        }

        return new self($data[0], $data[1]);
    }
}
