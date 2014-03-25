<?php

namespace Ekyna\Component\Table\Extension\Core;

use Ekyna\Component\Table\AbstractTableExtension;

/**
 * CoreExtension
 */
class CoreExtension extends AbstractTableExtension
{
    /**
     * {@inheritdoc}
     */
    protected function loadTableTypes()
    {
        return array(
        	new Type\TableType
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function loadColumnTypes()
    {
        return array(
        	new Type\Column\TextType,
            new Type\Column\NumberType,
            new Type\Column\DatetimeType,
            new Type\Column\AnchorType,
            new Type\Column\ActionsType,
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function loadFilterTypes()
    {
        return array(
            new Type\Filter\TextType,
            new Type\Filter\NumberType,
            new Type\Filter\DatetimeType,
        );
    }
}