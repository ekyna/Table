<?php

namespace Ekyna\Component\Table\Extension\Core\Type\Column;

use Ekyna\Component\Table\Column\AbstractColumnType;
use Ekyna\Component\Table\Column\ColumnInterface;
use Ekyna\Component\Table\Source\RowInterface;
use Ekyna\Component\Table\View\CellView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class BooleanType
 * @package Ekyna\Component\Table\Extension\Core\Type\Column
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class BooleanType extends AbstractColumnType
{
    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'true_label'            => 'Yes',
                'false_label'           => 'No',
                'true_class'            => 'label-success',
                'false_class'           => 'label-danger',
                'route_name'            => null,
                'route_parameters'      => [],
                'route_parameters_map'  => [],
                'disable_property_path' => null,
            ])
            ->setAllowedTypes('true_label', 'string')
            ->setAllowedTypes('false_label', 'string')
            ->setAllowedTypes('true_class', 'string')
            ->setAllowedTypes('false_class', 'string')
            ->setAllowedTypes('route_name', ['null', 'string'])
            ->setAllowedTypes('route_parameters', 'array')
            ->setAllowedTypes('route_parameters_map', 'array')
            ->setAllowedTypes('disable_property_path', ['null', 'string']);
    }

    /**
     * @inheritDoc
     */
    public function buildCellView(CellView $view, ColumnInterface $column, RowInterface $row, array $options)
    {
        $route = $options['route_name'];
        if (null !== $disablePath = $options['disable_property_path']) {
            if ($row->getData($disablePath)) {
                $route = null;
            }
        }

        if (null !== $route) {
            $parameters = $options['route_parameters'];
            foreach ($options['route_parameters_map'] as $key => $propertyPath) {
                $parameters[$key] = $row->getData($propertyPath);
            }
        } else {
            $parameters = [];
        }

        $view->vars['label'] = $view->vars['value'] ? $options['true_label'] : $options['false_label'];
        $view->vars['class'] = $view->vars['value'] ? $options['true_class'] : $options['false_class'];

        $view->vars['route'] = $route;
        $view->vars['parameters'] = $parameters;
    }

    /**
     * @inheritDoc
     */
    public function getParent()
    {
        return PropertyType::class;
    }
}
