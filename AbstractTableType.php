<?php

namespace Ekyna\Component\Table;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AbstractTableType
 * @package Ekyna\Component\Table
 * @author  Étienne Dauvergne <contact@ekyna.com>
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
    public function configureOptions(OptionsResolver $resolver)
    {
        $dataClass = function (Options $options) {
            return isset($options['data']) && is_object($options['data']) ? get_class($options['data']) : null;
        };

        $resolver->setDefaults([
            'name'            => $this->getName(),
            'data'            => null,
            'data_class'      => $dataClass,
            'em'              => null,
            'default_sorts'   => [],
            'max_per_page'    => 15,
            'customize_qb'    => null,
            'selector'        => false,
            'multiple'        => false,
            'selector_config' => null,
        ]);

        $resolver->setAllowedTypes('name', 'string');
        $resolver->setAllowedTypes('data', ['null', 'array', Collection::class]);
        $resolver->setAllowedTypes('data_class', 'string');
        $resolver->setAllowedTypes('em', ['null', ObjectManager::class]);
        $resolver->setAllowedTypes('default_sorts', 'array');
        $resolver->setAllowedTypes('max_per_page', 'int');
        $resolver->setAllowedTypes('customize_qb', ['null', 'callable']);
        $resolver->setAllowedTypes('selector', 'bool');
        $resolver->setAllowedTypes('multiple', 'bool');
        $resolver->setAllowedTypes('selector_config', ['null', 'array']);

        $resolver->setRequired(['data_class', 'name']);
    }

    /**
     * {@inheritdoc}
     */
    abstract function getName();
}
