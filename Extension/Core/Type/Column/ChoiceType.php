<?php

namespace Ekyna\Component\Table\Extension\Core\Type\Column;

use Ekyna\Component\Table\Table;
use Ekyna\Component\Table\View\Cell;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ChoiceType
 * @package Ekyna\Component\Table\Extension\Core\Type\Column
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class ChoiceType extends PropertyType
{
    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults(['choices' => null]);
        $resolver->setRequired(['choices']);
        $resolver->setAllowedTypes('choices', 'array');
    }

    /**
     * @inheritdoc
     */
    public function buildViewCell(Cell $cell, Table $table, array $options)
    {
        parent::buildViewCell($cell, $table, $options);

        $label = '&nbsp;';
        if (array_key_exists($cell->vars['value'], $options['choices'])) {
            $label = $options['choices'][$cell->vars['value']];
        }

        $cell->setVars(['label' => $label]);
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'choice';
    }
}
