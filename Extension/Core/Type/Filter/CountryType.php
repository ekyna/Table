<?php

namespace Ekyna\Component\Table\Extension\Core\Type\Filter;

use Symfony\Component\Intl\Intl;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CountryType
 * @package Ekyna\Component\Table\Extension\Core\Type\Filter
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class CountryType extends ChoiceType
{
    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'choices' => Intl::getRegionBundle()->getCountryNames(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'country';
    }
}
