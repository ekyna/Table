<?php

namespace Ekyna\Component\Table\View;

/**
 * Class AvailableFilter
 * @package Ekyna\Component\Table\View
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class AvailableFilter
{
    private $vars = array(
        'name'      => null,
        'full_name' => null,
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
