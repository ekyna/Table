<?php

namespace Ekyna\Component\Table\Extension\Core\Type\Column;

use Ekyna\Component\Table\AbstractColumnType;
use Ekyna\Component\Table\Table;
use Ekyna\Component\Table\View\Cell;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccessor;

/**
 * Class SelectorType
 * @package Ekyna\Component\Table\Extension\Core\Type\Column
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class SelectorType extends AbstractColumnType
{
    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'multiple'      => false,
            'property_path' => null,
            'data_map'      => null,
        ]);

        $resolver->setAllowedTypes('multiple', 'bool');
        $resolver->setAllowedTypes('property_path', ['null', 'string']);
        $resolver->setAllowedTypes('data_map', ['null', 'array']);
    }

    /**
     * @inheritdoc
     */
    public function buildViewCell(Cell $cell, Table $table, array $options)
    {
        parent::buildViewCell($cell, $table, $options);

        if (0 < strlen($options['property_path'])) {
            $value = $table->getCurrentRowData($options['property_path']);
        } else {
            $value = $table->getCurrentRowKey();
        }

        $data = [];
        if (is_array($options['data_map'])) {
            foreach ($options['data_map'] as $key => $property_path) {
                $data[(is_string($key) ? $key : $property_path)] = (string)$table->getCurrentRowData($property_path);
            }
        }

        $cell->setVars([
            'input_type' => $options['multiple'] ? 'checkbox' : 'radio',
            'input_name' => $options['full_name'],
            'value'      => $value,
            'data'       => $data,
            'selected'   => false,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'selector';
    }
}
