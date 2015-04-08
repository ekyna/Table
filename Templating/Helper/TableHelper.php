<?php

namespace Ekyna\Component\Table\Templating\Helper;

use Symfony\Component\Templating\Helper\Helper;
use Ekyna\Component\Table\TableRendererInterface;
use Ekyna\Component\Table\TableView;

/**
 * TableHelper provides helpers to help display tables.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class TableHelper extends Helper
{
    /**
     * @var TableRendererInterface
     */
    private $renderer;

    /**
     * @param TableRendererInterface $renderer
     */
    public function __construct(TableRendererInterface $renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'table';
    }

    /**
     * Sets a theme for a given view.
     *
     * The theme format is "<Bundle>:<Controller>".
     *
     * @param TableView     $view   A TableView instance
     * @param string|array $themes A theme or an array of theme
     */
    public function setTheme(TableView $view, $themes)
    {
        $this->renderer->setTheme($view, $themes);
    }

    /**
     * Renders the HTML for a table.
     *
     * Example usage:
     *
     *     <?php echo view['table']->table($table) ?>
     *
     * You can pass options during the call:
     *
     *     <?php echo view['table']->table($table, array('attr' => array('class' => 'foo'))) ?>
     *
     *     <?php echo view['table']->table($table, array('separator' => '+++++')) ?>
     *
     * This method is mainly intended for prototyping purposes. If you want to
     * control the layout of a table in a more fine-grained manner, you are
     * advised to use the other helper methods for rendering the parts of the
     * table individually. You can also create a custom table theme to adapt
     * the look of the table.
     *
     * @param TableView $view      The view for which to render the table
     * @param array    $variables Additional variables passed to the template
     *
     * @return string The HTML markup
     */
    public function table(TableView $view, array $variables = array())
    {
        return $this->renderer->renderBlock($view, 'table', $variables);
    }

    /**
     * Renders the table start tag.
     *
     * Example usage templates:
     *
     *     <?php echo $view['table']->start($table) ?>>
     *
     * @param TableView $view      The view for which to render the start tag
     * @param array    $variables Additional variables passed to the template
     *
     * @return string The HTML markup
     */
    public function start(TableView $view, array $variables = array())
    {
        return $this->renderer->renderBlock($view, 'table_start', $variables);
    }

    /**
     * Renders the table end tag.
     *
     * Example usage templates:
     *
     *     <?php echo $view['table']->end($table) ?>>
     *
     * @param TableView $view      The view for which to render the end tag
     * @param array    $variables Additional variables passed to the template
     *
     * @return string The HTML markup
     */
    public function end(TableView $view, array $variables = array())
    {
        return $this->renderer->renderBlock($view, 'table_end', $variables);
    }

    /**
     * Renders the HTML enctype in the table tag, if necessary.
     *
     * Example usage templates:
     *
     *     <table action="..." method="post" <?php echo $view['table']->enctype($table) ?>>
     *
     * @param TableView $view The view for which to render the encoding type
     *
     * @return string The HTML markup
     *
     * @deprecated Deprecated since version 2.3, to be removed in 3.0. Use
     *             {@link start} instead.
     */
    public function enctype(TableView $view)
    {
        // Uncomment this as soon as the deprecation note should be shown
        // trigger_error('The table helper $view[\'table\']->enctype() is deprecated since version 2.3 and will be removed in 3.0. Use $view[\'table\']->start() instead.', E_USER_DEPRECATED);
        return $this->renderer->searchAndRenderBlock($view, 'enctype');
    }

    /**
     * Renders the HTML for a given view.
     *
     * Example usage:
     *
     *     <?php echo $view['table']->widget($table) ?>
     *
     * You can pass options during the call:
     *
     *     <?php echo $view['table']->widget($table, array('attr' => array('class' => 'foo'))) ?>
     *
     *     <?php echo $view['table']->widget($table, array('separator' => '+++++')) ?>
     *
     * @param TableView $view      The view for which to render the widget
     * @param array    $variables Additional variables passed to the template
     *
     * @return string The HTML markup
     */
    public function widget(TableView $view, array $variables = array())
    {
        return $this->renderer->searchAndRenderBlock($view, 'widget', $variables);
    }

    /**
     * Renders the entire table field "row".
     *
     * @param TableView $view      The view for which to render the row
     * @param array    $variables Additional variables passed to the template
     *
     * @return string The HTML markup
     */
    public function row(TableView $view, array $variables = array())
    {
        return $this->renderer->searchAndRenderBlock($view, 'row', $variables);
    }

    /**
     * Renders the label of the given view.
     *
     * @param TableView $view      The view for which to render the label
     * @param string   $label     The label
     * @param array    $variables Additional variables passed to the template
     *
     * @return string The HTML markup
     */
    public function label(TableView $view, $label = null, array $variables = array())
    {
        if (null !== $label) {
            $variables += array('label' => $label);
        }

        return $this->renderer->searchAndRenderBlock($view, 'label', $variables);
    }

    /**
     * Renders the errors of the given view.
     *
     * @param TableView $view The view to render the errors for
     *
     * @return string The HTML markup
     */
    public function errors(TableView $view)
    {
        return $this->renderer->searchAndRenderBlock($view, 'errors');
    }

    /**
     * Renders views which have not already been rendered.
     *
     * @param TableView $view      The parent view
     * @param array    $variables An array of variables
     *
     * @return string The HTML markup
     */
    public function rest(TableView $view, array $variables = array())
    {
        return $this->renderer->searchAndRenderBlock($view, 'rest', $variables);
    }

    /**
     * Renders a block of the template.
     *
     * @param TableView $view      The view for determining the used themes.
     * @param string   $blockName The name of the block to render.
     * @param array    $variables The variable to pass to the template.
     *
     * @return string The HTML markup
     */
    public function block(TableView $view, $blockName, array $variables = array())
    {
        return $this->renderer->renderBlock($view, $blockName, $variables);
    }

    public function humanize($text)
    {
        return $this->renderer->humanize($text);
    }
}
