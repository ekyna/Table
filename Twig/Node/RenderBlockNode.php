<?php

namespace Ekyna\Component\Table\Twig\Node;

/**
 * Compiles a call to {@link FormRendererInterface::renderBlock()}.
 *
 * The function name is used as block name. For example, if the function name
 * is "foo", the block "foo" will be rendered.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class RenderBlockNode extends \Twig_Node_Expression_Function
{
    public function compile(\Twig_Compiler $compiler)
    {
        $compiler->addDebugInfo($this);
        $arguments = iterator_to_array($this->getNode('arguments'));
        $compiler->write('$this->env->getExtension(\'table\')->renderer->renderBlock(');

        if (isset($arguments[0])) {
            $compiler->subcompile($arguments[0]);
            $compiler->raw(', \''.$this->getAttribute('name').'\'');

            if (isset($arguments[1])) {
                $compiler->raw(', ');
                $compiler->subcompile($arguments[1]);
            }
        }

        $compiler->raw(')');
    }
}
