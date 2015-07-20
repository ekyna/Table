<?php

namespace Ekyna\Component\Table\Extension\Core;

use Ekyna\Component\Table\AbstractTableExtension;

/**
 * Class CoreExtension
 * @package Ekyna\Component\Table\Extension\Core
 * @author Étienne Dauvergne <contact@ekyna.com>
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
        	new Type\Column\SelectorType,
        	new Type\Column\TextType,
            new Type\Column\NumberType,
            new Type\Column\ChoiceType,
            new Type\Column\CountryType,
            new Type\Column\BooleanType,
            new Type\Column\DatetimeType,
            new Type\Column\ImageType,
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
            new Type\Filter\BooleanType,
            new Type\Filter\ChoiceType,
            new Type\Filter\CountryType,
        );
    }
}