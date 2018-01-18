<?php

namespace Ekyna\Component\Table;

use Ekyna\Component\Table\Source\RowInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Interface ResolvedTableTypeInterface
 * @package Ekyna\Component\Table
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
interface ResolvedTableTypeInterface
{
    /**
     * Returns the prefix of the template block name for this type.
     *
     * The block prefix defaults to the underscored short class name with
     * the "Type" suffix removed (e.g. "UserProfileType" => "user_profile").
     *
     * @return string The prefix of the template block name
     */
    public function getBlockPrefix();

    /**
     * Returns the parent type.
     *
     * @return self|null The parent type or null
     */
    public function getParent();

    /**
     * Returns the wrapped table type.
     *
     * @return TableTypeInterface The wrapped table type
     */
    public function getInnerType();

    /**
     * Returns the extensions of the wrapped table type.
     *
     * @return Extension\TableTypeExtensionInterface[] An array of {@link TableTypeExtensionInterface} instances
     */
    public function getTypeExtensions();

    /**
     * Creates a new table builder for this type.
     *
     * @param FactoryInterface $factory The table factory
     * @param string           $name    The name for the builder
     * @param array            $options The builder options
     *
     * @return TableBuilderInterface The created table builder
     */
    public function createBuilder(FactoryInterface $factory, $name, array $options = []);

    /**
     * Creates a new table view for a table of this type.
     *
     * @param TableInterface $table The table to create a view for
     *
     * @return View\TableView The created table view
     */
    public function createView(TableInterface $table);

    /**
     * Configures a table builder for the type hierarchy.
     *
     * @param TableBuilderInterface $builder The builder to configure
     * @param array                 $options The options used for the configuration
     */
    public function buildTable(TableBuilderInterface $builder, array $options);

    /**
     * Configures a table view for the type hierarchy.
     *
     * @param View\TableView $view    The table view to configure
     * @param TableInterface $table   The table corresponding to the view
     * @param array          $options The options used for the configuration
     */
    public function buildView(View\TableView $view, TableInterface $table, array $options);

    /**
     * Configures a row view for the type hierarchy.
     *
     * @param View\RowView $view    The row view to configure
     * @param RowInterface $row     The row corresponding to the view
     * @param array        $options The options used for the configuration
     */
    public function buildRowView(View\RowView $view, RowInterface $row, array $options);

    /**
     * Returns the configured options resolver used for this type.
     *
     * @return OptionsResolver The options resolver
     */
    public function getOptionsResolver();
}
