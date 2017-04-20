<?php

declare(strict_types=1);

namespace Ekyna\Component\Table\Filter\Form;

use Ekyna\Component\Table\Context\ActiveFilter;
use Ekyna\Component\Table\Exception\RuntimeException;
use Ekyna\Component\Table\Http\ParametersHelper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class FilterType
 * @package Ekyna\Component\Table\Filter\Form
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class FilterType extends AbstractType
{
    /**
     * @inheritDoc
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        /** @var ActiveFilter $activeFilter */
        $activeFilter = $form->getData();

        if (!$activeFilter) {
            throw new RuntimeException('Filter form data must be set.');
        }

        $view->vars['filter_param_name'] = ParametersHelper::ADD_FILTER;
        $view->vars['filter_param_value'] = $activeFilter->getFilterName();
    }

    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'method'     => 'GET',
            'data_class' => ActiveFilter::class,
        ]);
    }
}
