<?php

namespace Ekyna\Component\Table;

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class AbstractTableType
 * @package Ekyna\Component\Table
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
abstract class AbstractTableType implements TableTypeInterface
{
    /**
     * {@inheritdoc}
     */
    abstract public function buildTable(TableBuilderInterface $builder, array $options);

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $dataClass = function (Options $options) {
            return isset($options['data']) && is_object($options['data']) ? get_class($options['data']) : null;
        };

        $resolver->setDefaults(array(
            'name'            => $this->getName(),
            'data'            => null,
            'data_class'      => $dataClass,
            'em'              => null,
            'default_sorts'   => array(),
            'max_per_page'    => 15,
            'customize_qb'    => null,
            'selector'        => false,
            'multiple'        => false,
            'selector_config' => null,
        ));

        $resolver->setAllowedTypes(array(
            'name'            => 'string',
            'data'            => array('null', 'array', 'Doctrine\Common\Collections\Collection'),
            'data_class'      => 'string',
            'em'              => array('null', 'Doctrine\Common\Persistence\ObjectManager'),
            'default_sorts'   => 'array',
            'max_per_page'    => 'int',
            'customize_qb'    => array('null', 'callable'),
            'selector'        => 'bool',
            'multiple'        => 'bool',
            'selector_config' => array('null', 'array'),
        ));

        $resolver->setRequired(array('data_class', 'name'));
    }

    /**
     * {@inheritdoc}
     */
    abstract function getName();
}
