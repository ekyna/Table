<?php

namespace Ekyna\Component\Table;

use Ekyna\Component\Table\View\Cell;
use Ekyna\Component\Table\View\Column;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccessor;

/**
 * AbstractColumnType
 */
abstract class AbstractColumnType implements ColumnTypeInterface
{
    public function configureOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'name'      => null,
            'full_name' => null,
            'type'      => $this->getName(),
        ));
        $resolver->setRequired(array('name', 'full_name', 'type'));
        $resolver->setAllowedTypes(array(
            'name'      => 'string',
            'full_name' => 'string',
            'type'      => 'string',
        ));
    }

    public function buildTableColumn(Table $table, $name, array $options = array())
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);

        $options['name'] = $name;
        $options['full_name'] = sprintf('%s_%s', $table->getName(), $name);
        $resolvedOptions = $resolver->resolve($options);

        $table->addColumn($resolvedOptions);

        return $resolvedOptions;
    }

    public function buildViewColumn(Column $column, TableGenerator $generator, array $options)
    {
        $column->setVars(array(
            'name'      => $options['name'],
            'full_name' => $options['full_name'],
        ));
    }

    public function buildViewCell(Cell $cell, PropertyAccessor $propertyAccessor, $entity, array $options)
    {
        $cell->setVars(array(
            'type' => $options['type'],
        ));
    }
}