<?php

declare(strict_types=1);

namespace Ekyna\Component\Table\Extension\Core\Type\Column;

use Ekyna\Component\Table\Column\AbstractColumnType;
use Ekyna\Component\Table\Column\ColumnInterface;
use Ekyna\Component\Table\Source\RowInterface;
use Ekyna\Component\Table\View\CellView;
use IteratorAggregate;
use Symfony\Component\OptionsResolver\OptionsResolver;

use function array_search;
use function is_array;
use function is_null;
use function iterator_to_array;

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
    public function buildCellView(CellView $view, ColumnInterface $column, RowInterface $row, array $options): void
    {
        $value = $view->vars['value'];

        if ($value instanceof IteratorAggregate) {
            /** @noinspection PhpUnhandledExceptionInspection */
            $value = iterator_to_array($value->getIterator());
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
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver): void
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
    public function getParent(): ?string
    {
        return PropertyType::class;
    }
}
