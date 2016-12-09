<?php

namespace Ekyna\Component\Table\Extension\Core\Type\Column;

/**
 * Class IdType
 * @package Ekyna\Component\Table\Extension\Core\Type\Column
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class IdType extends PropertyType
{
    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'id';
    }
}
