<?php

namespace Ekyna\Component\Table\Extension\Core\Type\Column;

/**
 * Class NumberType
 * @package Ekyna\Component\Table\Extension\Core\Type\Column
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class NumberType extends PropertyType
{
    // TODO precision, (twig) number_format

    public function getName()
    {
    	return 'number';
    }
}
