<?php

namespace Ekyna\Component\Table\View;

/**
 * Class Cell
 * @package Ekyna\Component\Table\View
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class Cell
{
    public $vars = array(
        'value'  => null,
        'type'   => null,
    	'sorted' => false
    );

    public function setVars(array $vars)
    {
        $this->vars = array_merge($this->vars, $vars);
    }
}