<?php

namespace Ekyna\Component\Table;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Interface TableTypeInterface
 * @package Ekyna\Component\Table
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
interface TableTypeInterface
{
    /**
     * Builds the table.
     * 
     * @param TableBuilderInterface $builder
     */
    public function buildTable(TableBuilderInterface $builder);

    /**
     * Sets the default options.
     *
     * @param \Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver);

    /**
     * Returns the type name.
     *
     * @return string
     */
    public function getName();
}
