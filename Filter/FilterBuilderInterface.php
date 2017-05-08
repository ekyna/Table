<?php

namespace Ekyna\Component\Table\Filter;

/**
 * Interface FilterBuilderInterface
 * @package Ekyna\Component\Table\Filter
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
interface FilterBuilderInterface extends FilterConfigBuilderInterface
{
    /**
     * Returns the filter.
     *
     * @return Filter
     */
    public function getFilter();
}
