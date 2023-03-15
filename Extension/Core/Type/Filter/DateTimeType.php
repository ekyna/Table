<?php

declare(strict_types=1);

namespace Ekyna\Component\Table\Extension\Core\Type\Filter;

use DateTime;
use DateTimeInterface;
use Ekyna\Component\Table\Context\ActiveFilter;
use Ekyna\Component\Table\Filter\AbstractFilterType;
use Ekyna\Component\Table\Filter\FilterInterface;
use Ekyna\Component\Table\Util\FilterOperator;
use Ekyna\Component\Table\View\ActiveFilterView;
use Symfony\Component\Form\Extension\Core\Type as FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotNull;

use function in_array;

/**
 * Class DateTimeType
 * @package Ekyna\Component\Table\Extension\Core\Type\Filter
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class DateTimeType extends AbstractFilterType
{
    /**
     * @inheritDoc
     */
    public function buildActiveView(
        ActiveFilterView $view,
        FilterInterface  $filter,
        ActiveFilter     $activeFilter,
        array            $options
    ): void {
        /** @var DateTime $date */
        $date = $activeFilter->getValue();

        if (!$date instanceof DateTimeInterface) {
            $view->vars['value'] = '';

            return;
        }

        $view->vars['value'] = $date->format($options['time'] ? 'd/m/Y H:i' : 'd/m/Y'); // TODO localized format.
    }

    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, FilterInterface $filter, array $options): bool
    {
        $builder
            ->add('operator', FormType\ChoiceType::class, [
                'label'    => false,
                'required' => true,
                'choices'  => FilterOperator::getChoices([
                    FilterOperator::IS_NULL,
                    FilterOperator::IS_NOT_NULL,
                    FilterOperator::EQUAL,
                    FilterOperator::NOT_EQUAL,
                    FilterOperator::LOWER_THAN,
                    FilterOperator::LOWER_THAN_OR_EQUAL,
                    FilterOperator::GREATER_THAN,
                    FilterOperator::GREATER_THAN_OR_EQUAL,
                ]),
            ]);

        $valueType = $options['time'] ? FormType\DateTimeType::class : FormType\DateType::class;

        $this->addValueField($builder, $valueType);

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($valueType) {
            $operator = (int)$event->getData()['operator'] ?? null;

            $required = !in_array($operator, [FilterOperator::IS_NULL, FilterOperator::IS_NOT_NULL], true);

            $this->addValueField($event->getForm(), $valueType, $required);
        });

        return true;
    }

    private function addValueField(FormInterface|FormBuilderInterface $form, string $type, bool $required = false): void
    {
        $childOptions = [
            'label'    => false,
            'required' => false,
            'input'    => 'datetime',
        ];

        if ($required) {
            $childOptions['constraints'] = [
                new NotNull(),
            ];
        }

        $form
            ->remove('value')
            ->add('value', $type, $childOptions);
    }

    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefault('time', false)
            ->setAllowedTypes('time', 'bool');
    }

    /**
     * @inheritDoc
     */
    public function getParent(): ?string
    {
        return FilterType::class;
    }
}
