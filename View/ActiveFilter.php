<?php

namespace Ekyna\Component\Table\View;

class ActiveFilter
{
    private $vars = array(
        'name'      => null,
        'full_name' => null,
        'id'        => null,
        'label'     => null,
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
        if(isset($this->vars[$key])) {
            return $this->vars[$key];
        }
        return $default;
    }
    
    public function setVar($key, $value)
    {
        $this->vars[$key] = $value;
    }
}