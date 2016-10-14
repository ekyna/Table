<?php

namespace Ekyna\Component\Table\Extension\Core\Type\Column;

use Ekyna\Component\Table\Table;
use Ekyna\Component\Table\View\Cell;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class PriceType
 * @package Ekyna\Component\Table\Extension\Core\Type\Column
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class PriceType extends PropertyType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'currency'      => null,
            'currency_path' => null,
        ]);

        $resolver->setAllowedTypes('currency', ['null', 'string']);
        $resolver->setAllowedTypes('currency_path', ['null', 'string']);
    }

    /**
     * {@inheritdoc}
     */
    public function buildViewCell(Cell $cell, Table $table, array $options)
    {
        parent::buildViewCell($cell, $table, $options);

        $currency = $options['currency'];
        if (null === $currency && 0 < strlen($path = $options['currency_path'])) {
            $currency = $table->getCurrentRowData($path);
        }

        $cell->setVars([
            'currency' => $currency,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'price';
    }
}
