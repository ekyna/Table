<?php

declare(strict_types=1);

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
use Symfony\Component\Validator\Constraints\NotNull;

use function array_keys;

/**
 * Class BooleanType
 * @package Ekyna\Component\Table\Extension\Core\Type\Filter
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class BooleanType extends AbstractFilterType
{
    public const MODE_DEFAULT     = 'default';
    public const MODE_IS_NULL     = 'is_null';
    public const MODE_IS_NOT_NULL = 'is_not_null';


    public function buildActiveView(
        ActiveFilterView $view,
        FilterInterface  $filter,
        ActiveFilter     $activeFilter,
        array            $options
    ): void {
        $view->vars['value'] = array_keys($options['choices'])[$activeFilter->getValue() ? 1 : 0];
    }

    public function buildForm(FormBuilderInterface $builder, FilterInterface $filter, array $options): bool
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
                'label'       => false,
                'required'    => true,
                'choices'     => $options['choices'],
                'constraints' => [
                    new NotNull(),
                ],
            ]);

        return true;
    }

    public function applyFilter(
        AdapterInterface $adapter,
        FilterInterface  $filter,
        ActiveFilter     $activeFilter,
        array            $options
    ): bool {
        if (!$adapter instanceof ArrayAdapter) {
            return false;
        }

        $operator = $activeFilter->getOperator();
        $value = $activeFilter->getValue();

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

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefaults([
                'mode'    => self::MODE_DEFAULT,
                'choices' => [
                    'No'  => false,
                    'Yes' => true,
                ],
            ])
            ->setAllowedTypes('mode', 'string')
            ->setAllowedValues('mode', [self::MODE_DEFAULT, self::MODE_IS_NULL, self::MODE_IS_NOT_NULL]);
    }

    public function getParent(): ?string
    {
        return FilterType::class;
    }
}
