<?php

namespace Ekyna\Component\Table\Extension\Core\Type\Column;

use Ekyna\Component\Table\Table;
use Ekyna\Component\Table\View\Cell;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class AnchorType
 * @package Ekyna\Component\Table\Extension\Core\Type\Column
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class AnchorType extends PropertyType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolverInterface $resolver)
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefaults(array(
                'route_name'           => null,
                'route_parameters'     => array(),
                'route_parameters_map' => array(),
            ))
            ->setRequired(array('route_name'))
            ->setAllowedTypes(array(
                'route_name'           => 'string',
                'route_parameters'     => 'array',
                'route_parameters_map' => 'array',
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function buildViewCell(Cell $cell, Table $table, array $options)
    {
        parent::buildViewCell($cell, $table, $options);

        if (!empty($options['route_parameters_map'])) {
            $parameters = [];
            foreach($options['route_parameters_map'] as $parameter => $propertyPath) {
                if (null !== $value = $table->getCurrentRowData($propertyPath)) {
                    $parameters[$parameter] = $value;
                }
            }
            if (0 < count(array_diff_key($options['route_parameters_map'], $parameters))) {
                return;
            }
            $parameters = array_replace($parameters, $options['route_parameters']);
        } else {
            $parameters = $options['route_parameters'];
        }

        $cell->setVars(array(
            'route'      => $options['route_name'],
            'parameters' => $parameters,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'anchor';
    }
}
