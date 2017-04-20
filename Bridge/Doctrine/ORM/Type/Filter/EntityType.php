<?php

declare(strict_types=1);

namespace Ekyna\Component\Table\Bridge\Doctrine\ORM\Type\Filter;

use Closure;
use Doctrine\Persistence\ManagerRegistry;
use Ekyna\Component\Table\Bridge\Doctrine\ORM\Form\IdToObjectTransformer;
use Ekyna\Component\Table\Context\ActiveFilter;
use Ekyna\Component\Table\Exception\InvalidArgumentException;
use Ekyna\Component\Table\Extension\Core\Type\Filter\FilterType;
use Ekyna\Component\Table\Filter\AbstractFilterType;
use Ekyna\Component\Table\Filter\FilterInterface;
use Ekyna\Component\Table\Util\FilterOperator;
use Ekyna\Component\Table\View\ActiveFilterView;
use Symfony\Bridge\Doctrine\Form\Type\EntityType as FormEntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Validator\Constraints\Count;

use function array_shift;
use function explode;
use function implode;
use function is_string;
use function strpos;

/**
 * Class EntityType
 * @package Ekyna\Component\Table\Bridge\Doctrine\ORM\Type\Filter
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class EntityType extends AbstractFilterType
{
    private ManagerRegistry $registry;
    private ?PropertyAccessorInterface $propertyAccessor = null;


    /**
     * Constructor.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, FilterInterface $filter, array $options): bool
    {
        $valueField = $builder
            ->create('value', $options['form_class'], $options['form_options'])
            ->addModelTransformer(
                new IdToObjectTransformer($this->registry->getRepository($options['class']), $options['identifier'])
            );

        if ($dataClass = $filter->getTable()->getConfig()->getDataClass()) {
            $operators = $this->getOperators($dataClass, $filter->getConfig()->getPropertyPath());
        } else {
            $operators = [
                FilterOperator::IN,
                FilterOperator::NOT_IN,
            ];
        }

        $builder
            ->add('operator', ChoiceType::class, [
                'label'   => false,
                'choices' => FilterOperator::getChoices($operators),
            ])
            ->add($valueField);

        return true;
    }

    /**
     * @inheritDoc
     */
    public function buildActiveView(
        ActiveFilterView $view,
        FilterInterface $filter,
        ActiveFilter $activeFilter,
        array $options
    ): void {
        $ids = $activeFilter->getValue();
        $entities = $this->registry->getRepository($options['class'])->findBy(['id' => $ids]);
        $values = [];

        $choiceLabel = $options['entity_label'];
        if (!$choiceLabel instanceof Closure) {
            if (is_string($choiceLabel) && !empty($choiceLabel)) {
                $accessor = $this->getPropertyAccessor();
                $propertyPath = $choiceLabel;
                $choiceLabel = function ($entity) use ($accessor, $propertyPath) {
                    return $accessor->getValue($entity, $propertyPath);
                };
            } else {
                $choiceLabel = function ($entity) {
                    return (string)$entity;
                };
            }
        }

        foreach ($entities as $entity) {
            $values[] = $choiceLabel($entity);
        }

        $view->vars['value'] = $values;
    }

    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setRequired('class')
            ->setDefaults([
                'identifier'    => 'id',
                'form_class'    => FormEntityType::class,
                'form_options'  => function (Options $options, $value) {
                    if (!empty($value)) {
                        return $value;
                    }

                    $value = [
                        'label'       => false,
                        'required'    => false,
                        'multiple'    => true,
                        'constraints' => [
                            new Count(['min' => 1]),
                        ],
                    ];

                    if ($options['form_class'] !== FormEntityType::class) {
                        return $value;
                    }

                    $value['class'] = $options['class'];
                    if ($options['entity_label']) {
                        $value['choice_label'] = $options['entity_label'];
                    }
                    if ($options['query_builder']) {
                        $value['query_builder'] = $options['query_builder'];
                    }

                    return $value;
                },
                'entity_label'  => null,
                'query_builder' => null,
            ])
            ->setAllowedTypes('class', 'string')
            ->setAllowedTypes('identifier', 'string')
            ->setAllowedTypes('form_class', 'string')
            ->setAllowedTypes('form_options', ['null', 'array'])
            ->setAllowedTypes('entity_label', ['null', 'string', 'closure'])
            ->setAllowedTypes('query_builder', ['null', 'closure']);
    }

    /**
     * @inheritDoc
     */
    public function getParent(): ?string
    {
        return FilterType::class;
    }

    /**
     * Returns the operators according to association type targeted by the property path.
     *
     * @param $dataClass
     * @param $propertyPath
     *
     * @return array
     */
    private function getOperators($dataClass, $propertyPath): array
    {
        $metadata = $this->registry->getManagerForClass($dataClass)->getClassMetadata($dataClass);

        if (false === strpos($propertyPath, '.')) {
            if ($metadata->hasAssociation($propertyPath)) {
                if ($metadata->isCollectionValuedAssociation($propertyPath)) {
                    return [
                        FilterOperator::MEMBER,
                        FilterOperator::NOT_MEMBER,
                    ];
                } else {
                    return [
                        FilterOperator::IN,
                        FilterOperator::NOT_IN,
                    ];
                }
            }
        } else {
            $paths = explode('.', $propertyPath);
            $path = array_shift($paths);

            if ($metadata->hasAssociation($path)) {
                return $this->getOperators(
                    $metadata->getAssociationTargetClass($path),
                    implode('.', $paths)
                );
            }
        }

        throw new InvalidArgumentException("Invalid property path '{$propertyPath}'.");
    }

    /**
     * Returns the property accessor.
     *
     * @return PropertyAccessorInterface
     */
    private function getPropertyAccessor(): PropertyAccessorInterface
    {
        if ($this->propertyAccessor) {
            return $this->propertyAccessor;
        }

        return $this->propertyAccessor = PropertyAccess::createPropertyAccessor();
    }
}
