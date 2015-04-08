<?php

namespace Ekyna\Component\Table\Twig\Node;

/**
 * @author Fabien Potencier <fabien@symfony.com>
 */
class TableThemeNode extends \Twig_Node
{
    public function __construct(\Twig_NodeInterface $table, \Twig_NodeInterface $resources, $lineno, $tag = null)
    {
        parent::__construct(array('table' => $table, 'resources' => $resources), array(), $lineno, $tag);
    }

    /**
     * Compiles the node to PHP.
     *
     * @param \Twig_Compiler $compiler A Twig_Compiler instance
     */
    public function compile(\Twig_Compiler $compiler)
    {
        $compiler
            ->addDebugInfo($this)
            ->write('$this->env->getExtension(\'table\')->renderer->setTheme(')
            ->subcompile($this->getNode('table'))
            ->raw(', ')
            ->subcompile($this->getNode('resources'))
            ->raw(");\n");
    }
}
