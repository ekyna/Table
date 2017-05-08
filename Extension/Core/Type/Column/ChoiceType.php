<?php

namespace Ekyna\Component\Table\Extension\Core\Type\Column;

use Ekyna\Component\Table\Column\AbstractColumnType;
use Ekyna\Component\Table\Column\ColumnInterface;
use Ekyna\Component\Table\Source\RowInterface;
use Ekyna\Component\Table\View\CellView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ChoiceType
 * @package Ekyna\Component\Table\Extension\Core\Type\Column
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class ChoiceType extends AbstractColumnType
{
    /**
     * @inheritDoc
     */
    public function buildCellView(CellView $view, ColumnInterface $column, RowInterface $row, array $options)
    {
        $value = $view->vars['value'];

        if ($value instanceof \IteratorAggregate) {
            $value = (array) $value->getIterator();
        } elseif (is_null($value)) {
            $value = [];
        } elseif (!is_array($value)) {
            $value = [$value];
        }

        $viewChoices = [];
        $choices = $options['choices'];

        foreach ($value as $val) {
            $choice = ['value' => $val];
            if ($options['choices_as_values']) {
                if (false !== $key = array_search($val, $choices, true)) {
                    $choice['label'] = $key;
                }
            } elseif (isset($choices[$val])) {
                $choice['label'] = $choices[$val];
            } else {
                continue;
            }
            $viewChoices[] = $choice;
        }

        $view->vars['value'] = $viewChoices;
    }

    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired('choices')
            ->setDefault('choices_as_values', true)
            ->setAllowedTypes('choices', 'array')
            ->setAllowedTypes('choices_as_values', 'bool');
    }

    /**
     * @inheritDoc
     */
    public function getParent()
    {
        return PropertyType::class;
    }
}
