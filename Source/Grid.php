<?php

declare(strict_types=1);

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
    private Pagerfanta $pager;
    /** @var Row[] */
    private array                       $rows;
    protected PropertyAccessorInterface $propertyAccessor;


    /**
     * Constructor.
     *
     * @param Pagerfanta                     $pager
     * @param PropertyAccessorInterface|null $accessor
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
    public function getPager(): Pagerfanta
    {
        return $this->pager;
    }

    /**
     * Returns the rows.
     *
     * @return Row[]
     */
    public function getRows(): array
    {
        return $this->rows;
    }

    /**
     * Adds the data row.
     *
     * @param RowInterface $row
     */
    public function addRow(RowInterface $row): void
    {
        $this->rows[] = $row;
    }
}
