<?php

declare(strict_types=1);

namespace Ekyna\Component\Table\Extension\Core;

use Ekyna\Component\Table\Extension\AbstractTableExtension;
use Ekyna\Component\Table\Extension\Core\Type\Extension\ExportTypeExtension;

/**
 * Class CoreExtension
 * @package Ekyna\Component\Table\Extension\Core
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class CoreExtension extends AbstractTableExtension
{
    /**
     * @inheritDoc
     */
    protected function loadTableTypes(): array
    {
        return [
            new Type\TableType(),
        ];
    }

    /**
     * @inheritDoc
     */
    protected function loadColumnTypes(): array
    {
        return [
            new Type\Column\BooleanType(),
            new Type\Column\ChoiceType(),
            new Type\Column\DateTimeType(),
            new Type\Column\NumberType(),
            new Type\Column\TextType(),
        ];
    }

    /**
     * @inheritDoc
     */
    protected function loadFilterTypes(): array
    {
        return [
            new Type\Filter\BooleanType(),
            new Type\Filter\ChoiceType(),
            new Type\Filter\DateTimeType(),
            new Type\Filter\NumberType(),
            new Type\Filter\TextType(),
        ];
    }

    /**
     * @inheritDoc
     */
    protected function loadTableTypeExtensions(): array
    {
        return [
            new ExportTypeExtension(),
        ];
    }

    /**
     * @inheritDoc
     */
    protected function loadAdapterFactories(): array
    {
        return [
            new Source\ArrayAdapterFactory(),
        ];
    }
}
