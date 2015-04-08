<?php

namespace Ekyna\Component\Table;

/**
 * Renders a table into HTML.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
interface TableRendererInterface
{
    /**
     * Returns the engine used by this renderer.
     *
     * @return TableRendererEngineInterface The renderer engine.
     */
    public function getEngine();

    /**
     * Sets the theme(s) to be used for rendering a view and its children.
     *
     * @param TableView $view   The view to assign the theme(s) to.
     * @param mixed    $themes The theme(s). The type of these themes
     *                         is open to the implementation.
     */
    public function setTheme(TableView $view, $themes);

    /**
     * Renders a named block of the table theme.
     *
     * @param TableView $view      The view for which to render the block.
     * @param string   $blockName The name of the block.
     * @param array    $variables The variables to pass to the template.
     *
     * @return string The HTML markup
     */
    public function renderBlock(TableView $view, $blockName, array $variables = array());

    /**
     * Searches and renders a block for a given name suffix.
     *
     * The block is searched by combining the block names stored in the
     * table view with the given suffix. If a block name is found, that
     * block is rendered.
     *
     * If this method is called recursively, the block search is continued
     * where a block was found before.
     *
     * @param TableView $view            The view for which to render the block.
     * @param string   $blockNameSuffix The suffix of the block name.
     * @param array    $variables       The variables to pass to the template.
     *
     * @return string The HTML markup
     */
    public function searchAndRenderBlock(TableView $view, $blockNameSuffix, array $variables = array());

    /**
     * Makes a technical name human readable.
     *
     * Sequences of underscores are replaced by single spaces. The first letter
     * of the resulting string is capitalized, while all other letters are
     * turned to lowercase.
     *
     * @param string $text The text to humanize.
     *
     * @return string The humanized text.
     */
    public function humanize($text);
}
