<?php

namespace Ekyna\Component\Table\View;

use Ekyna\Component\Table\Util\ColumnSort;

class Column
{
    private $vars = array(
        'name'      => null,
        'full_name' => null,
        'label'     => null,
    	'sortable'  => false,
    	'sort_dir'  => ColumnSort::NONE
    );

    public function setVars(array $vars)
    {
        $this->vars = array_merge($this->vars, $vars);
    }

    public function getVars()
    {
        return $this->vars;
    }

    public function getVar($key, $default = null)
    {
        if (array_key_exists($key, $this->vars)) {
            return $this->vars[$key];
        }
        return $default;
    }

    public function setVar($key, $value)
    {
        $this->vars[$key] = $value;
    }
}