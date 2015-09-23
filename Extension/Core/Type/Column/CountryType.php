<?php

namespace Ekyna\Component\Table\Extension\Core\Type\Column;

use Ekyna\Component\Table\Table;
use Ekyna\Component\Table\View\Cell;
use Symfony\Component\Intl\Intl;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CountryType
 * @package Ekyna\Component\Table\Extension\Core\Type\Column
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class CountryType extends ChoiceType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefaults([
                'choices' => Intl::getRegionBundle()->getCountryNames(),
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function buildViewCell(Cell $cell, Table $table, array $options)
    {
        parent::buildViewCell($cell, $table, $options);

        $label = '&nbsp;';
        if (array_key_exists($cell->vars['value'], $options['choices'])) {
            $label = Intl::getRegionBundle()->getCountryName($cell->vars['value']);
        }

        $cell->setVars([
            'label' =>  $label,
            'type'  => 'choice',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'country';
    }
}
