<?php

declare(strict_types=1);

namespace Ekyna\Component\Table\Extension\Core\Type\Column;

use Ekyna\Component\Table\Column\AbstractColumnType;
use Ekyna\Component\Table\Column\ColumnInterface;
use Ekyna\Component\Table\Source\RowInterface;
use Ekyna\Component\Table\View\CellView;
use Symfony\Component\OptionsResolver\OptionsResolver;

use function array_diff_key;
use function count;

/**
 * Class BooleanType
 * @package Ekyna\Component\Table\Extension\Core\Type\Column
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class BooleanType extends AbstractColumnType
{
    public function buildCellView(CellView $view, ColumnInterface $column, RowInterface $row, array $options): void
    {
        $view->vars['label'] = $view->vars['value'] ? $options['true_label'] : $options['false_label'];
        $view->vars['class'] = $view->vars['value'] ? $options['true_class'] : $options['false_class'];

        if (isset($view->vars['path']) || (isset($view->vars['route']) && isset($view->vars['parameters']))) {
            return;
        }

        if (null !== $disablePath = $options['disable_property_path']) {
            if ($row->getData($disablePath)) {
                return;
            }
        }

        $parameters = $options['parameters'];
        if (!empty($options['parameters_map'])) {
            foreach ($options['parameters_map'] as $parameter => $propertyPath) {
                if (null !== $value = $row->getData($propertyPath)) {
                    $parameters[$parameter] = $value;
                }
            }

            if (0 < count(array_diff_key($options['parameters_map'], $parameters))) {
                return;
            }
        }

        $view->vars['route']      = $options['route'];
        $view->vars['parameters'] = $parameters;
    }

    public function export(ColumnInterface $column, RowInterface $row, array $options): ?string
    {
        return $row->getData($column->getConfig()->getPropertyPath())
            ? $options['true_label']
            : $options['false_label'];
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'true_label'            => 'Yes',
                'false_label'           => 'No',
                'true_class'            => 'label-success',
                'false_class'           => 'label-danger',
                'route'                 => null,
                'parameters'            => [],
                'parameters_map'        => [],
                'disable_property_path' => null,
            ])
            ->setAllowedTypes('true_label', 'string')
            ->setAllowedTypes('false_label', 'string')
            ->setAllowedTypes('true_class', 'string')
            ->setAllowedTypes('false_class', 'string')
            ->setAllowedTypes('route', ['null', 'string'])
            ->setAllowedTypes('parameters', 'array')
            ->setAllowedTypes('parameters_map', 'array')
            ->setAllowedTypes('disable_property_path', ['null', 'string']);
    }

    public function getParent(): ?string
    {
        return PropertyType::class;
    }
}
