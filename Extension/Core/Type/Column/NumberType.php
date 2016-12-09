<?php

namespace Ekyna\Component\Table\Extension\Core\Type\Column;

use Ekyna\Component\Table\Table;
use Ekyna\Component\Table\View\Cell;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class NumberType
 * @package Ekyna\Component\Table\Extension\Core\Type\Column
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class NumberType extends PropertyType
{
    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'precision' => 2,
            'append'    => null,
        ]);

        $resolver
            ->setAllowedTypes('precision', 'int')
            ->setAllowedTypes('append', ['null', 'string']);
    }

    /**
     * @inheritdoc
     */
    public function buildViewCell(Cell $cell, Table $table, array $options)
    {
        parent::buildViewCell($cell, $table, $options);

        $cell->setVars([
            'append'    => $options['append'],
            'precision' => $options['precision'],
        ]);
    }

    public function getName()
    {
        return 'number';
    }
}
