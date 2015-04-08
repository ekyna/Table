<?php

namespace Ekyna\Component\Table\Twig\Extension;

use Ekyna\Component\Table\Twig\Renderer\TwigRendererInterface;
use Ekyna\Component\Table\Twig\TokenParser\TableThemeTokenParser;

/**
 * TableExtension extends Twig with table capabilities.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class TableExtension extends \Twig_Extension
{
    /**
     * This property is public so that it can be accessed directly from compiled
     * templates without having to call a getter, which slightly decreases pertableance.
     *
     * @var TwigRendererInterface
     */
    public $renderer;

    public function __construct(TwigRendererInterface $renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * {@inheritdoc}
     */
    public function initRuntime(\Twig_Environment $environment)
    {
        $this->renderer->setEnvironment($environment);
    }

    /**
     * {@inheritdoc}
     */
    public function getTokenParsers()
    {
        return array(
            // {% table_theme table "SomeBundle::widgets.twig" %}
            new TableThemeTokenParser(),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('table_widget', null, array('node_class' => 'Ekyna\Component\Table\Twig\Node\SearchAndRenderBlockNode', 'is_safe' => array('html'))),
            new \Twig_SimpleFunction('table_errors', null, array('node_class' => 'Ekyna\Component\Table\Twig\Node\SearchAndRenderBlockNode', 'is_safe' => array('html'))),
            new \Twig_SimpleFunction('table_label',  null, array('node_class' => 'Ekyna\Component\Table\Twig\Node\SearchAndRenderBlockNode', 'is_safe' => array('html'))),
            new \Twig_SimpleFunction('table_row',    null, array('node_class' => 'Ekyna\Component\Table\Twig\Node\SearchAndRenderBlockNode', 'is_safe' => array('html'))),
            new \Twig_SimpleFunction('table_rest',   null, array('node_class' => 'Ekyna\Component\Table\Twig\Node\SearchAndRenderBlockNode', 'is_safe' => array('html'))),
            new \Twig_SimpleFunction('table',        null, array('node_class' => 'Ekyna\Component\Table\Twig\Node\RenderBlockNode', 'is_safe' => array('html'))),
            new \Twig_SimpleFunction('table_start',  null, array('node_class' => 'Ekyna\Component\Table\Twig\Node\RenderBlockNode', 'is_safe' => array('html'))),
            new \Twig_SimpleFunction('table_end',    null, array('node_class' => 'Ekyna\Component\Table\Twig\Node\RenderBlockNode', 'is_safe' => array('html'))),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'table';
    }
}
