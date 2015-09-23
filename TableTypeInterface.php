<?php

namespace Ekyna\Component\Table;

use Symfony\Component\OptionsResolver\OptionsResolver;

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
     * @param array                 $options
     */
    public function buildTable(TableBuilderInterface $builder, array $options);

    /**
     * Sets the default options.
     *
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver);

    /**
     * Returns the type name.
     *
     * @return string
     */
    public function getName();
}
