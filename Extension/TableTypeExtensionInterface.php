<?php


namespace Ekyna\Component\Table\Extension;

use Ekyna\Component\Table\TableBuilderInterface;
use Ekyna\Component\Table\TableInterface;
use Ekyna\Component\Table\View\TableView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Interface TableTypeExtensionInterface
 * @package Ekyna\Component\Table
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
interface TableTypeExtensionInterface
{
    /**
     * Builds the table.
     *
     * This method is called after the extended type has built the table to
     * further modify it.
     *
     * @see TableTypeInterface::buildTable()
     *
     * @param TableBuilderInterface $builder The table builder
     * @param array                $options The options
     */
    public function buildTable(TableBuilderInterface $builder, array $options);

    /**
     * Builds the view.
     *
     * This method is called after the extended type has built the view to
     * further modify it.
     *
     * @see TableTypeInterface::buildView()
     *
     * @param \Ekyna\Component\Table\View\TableView $view    The view
     * @param TableInterface                        $table   The table
     * @param array                                 $options The options
     */
    public function buildView(TableView $view, TableInterface $table, array $options);

    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options
     */
    public function configureOptions(OptionsResolver $resolver);

    /**
     * Returns the name of the type being extended.
     *
     * @return string The name of the type being extended
     */
    public function getExtendedType();
}
