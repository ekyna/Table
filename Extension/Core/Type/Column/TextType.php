<?php

namespace Ekyna\Component\Table\Extension\Core\Type\Column;

/**
 * Class TextType
 * @package Ekyna\Component\Table\Extension\Core\Type\Column
 * @author �tienne Dauvergne <contact@ekyna.com>
 */
class TextType extends PropertyType
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
    	return 'text';
    }
}
