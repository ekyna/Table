<?php

namespace Ekyna\Component\Table\Extension\Core;

use Ekyna\Component\Table\Extension\AbstractTableExtension;

/**
 * Class CoreExtension
 * @package Ekyna\Component\Table\Extension\Core
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class CoreExtension extends AbstractTableExtension
{
    /**
     * @inheritdoc
     */
    protected function loadTableTypes()
    {
        return [
            new Type\TableType,
        ];
    }

    /**
     * @inheritdoc
     */
    protected function loadColumnTypes()
    {
        return [
            new Type\Column\BooleanType,
            new Type\Column\ChoiceType,
            new Type\Column\DateTimeType,
            new Type\Column\NumberType,
            new Type\Column\TextType,
        ];
    }

    /**
     * @inheritdoc
     */
    protected function loadFilterTypes()
    {
        return [
            new Type\Filter\BooleanType,
            new Type\Filter\ChoiceType,
            new Type\Filter\DateTimeType,
            new Type\Filter\NumberType,
            new Type\Filter\TextType,
        ];
    }

    /**
     * @inheritDoc
     */
    protected function loadAdapterFactories()
    {
        return [
            new Source\ArrayAdapterFactory,
        ];
    }
}
