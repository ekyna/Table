<?php

namespace Ekyna\Component\Table\Extension\Core\Type\Column;

use Ekyna\Component\Table\TableGenerator;
use Ekyna\Component\Table\View\Column;

/**
 * Class ImageType
 * @package Ekyna\Component\Table\Extension\Core\Type\Column
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class ImageType extends PropertyType
{
    public function buildViewColumn(Column $column, TableGenerator $generator, array $options)
    {
        parent::buildViewColumn($column, $generator, $options);

        $column->setVar('sortable', false);
    }

    public function getName()
    {
        return 'image';
    }
} 