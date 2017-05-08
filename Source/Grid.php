<?php

namespace Ekyna\Component\Table\Source;

use Pagerfanta\Pagerfanta;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

/**
 * Class Grid
 * @package Ekyna\Component\Table\Source
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class Grid
{
    /**
     * @var \Pagerfanta\Pagerfanta
     */
    private $pager;

    /**
     * @var Row[]
     */
    private $rows;

    /**
     * @var \Symfony\Component\PropertyAccess\PropertyAccessorInterface
     */
    protected $propertyAccessor;


    /**
     * Constructor.
     *
     * @param Pagerfanta                $pager
     * @param PropertyAccessorInterface $accessor
     */
    public function __construct(Pagerfanta $pager, PropertyAccessorInterface $accessor = null)
    {
        $this->pager = $pager;
        $this->rows = [];
        $this->propertyAccessor = $accessor ?: PropertyAccess::createPropertyAccessor();
    }

    /**
     * Returns the pager.
     *
     * @return Pagerfanta
     */
    public function getPager()
    {
        return $this->pager;
    }

    /**
     * Returns the rows.
     *
     * @return Row[]
     */
    public function getRows()
    {
        return $this->rows;
    }

    /**
     * Adds the data row.
     *
     * @param RowInterface $row
     */
    public function addRow(RowInterface $row)
    {
        $this->rows[] = $row;
    }
}
