<?php

namespace Ekyna\Component\Table\Extension\Core\Type\Filter;

use Ekyna\Component\Table\Context\ActiveFilter;
use Ekyna\Component\Table\Extension\Core\Source\ArrayAdapter;
use Ekyna\Component\Table\Filter\AbstractFilterType;
use Ekyna\Component\Table\Filter\FilterInterface;
use Ekyna\Component\Table\Source\AdapterInterface;
use Ekyna\Component\Table\Util\FilterOperator;
use Ekyna\Component\Table\View\ActiveFilterView;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class BooleanType
 * @package Ekyna\Component\Table\Extension\Core\Type\Filter
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class BooleanType extends AbstractFilterType
{
    const MODE_DEFAULT     = 'default';
    const MODE_IS_NULL     = 'is_null';
    const MODE_IS_NOT_NULL = 'is_not_null';


    /**
     * @inheritDoc
     */
    public function buildActiveView(ActiveFilterView $view, FilterInterface $filter, ActiveFilter $activeFilter, array $options)
    {
        $view->vars['value'] = $activeFilter->getValue() === 'yes' ? 'ekyna_core.value.yes' : 'ekyna_core.value.no';
    }

    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, FilterInterface $filter, array $options)
    {
        $builder
            ->add('operator', ChoiceType::class, [
                'label'    => false,
                'required' => true,
                'choices'  => FilterOperator::getChoices([
                    FilterOperator::EQUAL,
                    FilterOperator::NOT_EQUAL,
                ]),
            ])
            ->add('value', ChoiceType::class, [
                'label'    => false,
                'required' => true,
                'choices'  => [
                    // TODO component's own translations
                    'ekyna_core.value.yes' => 'yes',
                    'ekyna_core.value.no'  => 'no',
                ],
                'constraints' => [
                    new NotBlank()
                ],
            ]);

        return true;
    }

    /**
     * @inheritdoc
     */
    public function applyFilter(AdapterInterface $adapter, FilterInterface $filter, ActiveFilter $activeFilter, array $options)
    {
        if (!$adapter instanceof ArrayAdapter) {
            return false;
        }

        $operator = $activeFilter->getOperator();
        $value = $activeFilter->getValue() === 'yes';

        if ($options['mode'] !== self::MODE_DEFAULT) {
            $value = $options['mode'] === self::MODE_IS_NULL ? $value : !$value;
            $operator = $value ? FilterOperator::IS_NULL : FilterOperator::IS_NOT_NULL;
        }

        $adapter->addFilterClosure(
            $adapter->buildFilterClosure(
                $filter->getConfig()->getPropertyPath(),
                $operator,
                $value
            )
        );

        return true;
    }

    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefaults([
                'mode' => self::MODE_DEFAULT,
            ])
            ->setAllowedTypes('mode', 'string')
            ->setAllowedValues('mode', [self::MODE_DEFAULT, self::MODE_IS_NULL, self::MODE_IS_NOT_NULL]);
    }

    /**
     * @inheritdoc
     */
    public function getParent()
    {
        return FilterType::class;
    }
}
