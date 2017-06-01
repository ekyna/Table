<?php

namespace Ekyna\Component\Table\Bridge\Doctrine\ORM\Type\Filter;

use Doctrine\Common\Persistence\ManagerRegistry;
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
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * Class EntityType
 * @package Ekyna\Component\Table\Bridge\Doctrine\ORM\Type\Filter
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class EntityType extends AbstractFilterType
{
    /**
     * @var ManagerRegistry
     */
    private $registry;

    /**
     * @var \Symfony\Component\PropertyAccess\PropertyAccessorInterface
     */
    private $propertyAccessor;


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
    public function buildForm(FormBuilderInterface $builder, FilterInterface $filter, array $options)
    {
        $valueOptions = [
            'label'         => false,
            'class'         => $options['class'],
            'required'      => true,
            'multiple'      => true,
        ];
        if ($options['entity_label']) {
            $valueOptions['choice_label'] = $options['entity_label'];
        }
        if ($options['query_builder']) {
            $valueOptions['query_builder'] = $options['query_builder'];
        }

        $valueField = $builder
            ->create('value', FormEntityType::class, $valueOptions)
            ->addModelTransformer(
                new IdToObjectTransformer($this->getRepository($options))
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
    public function buildActiveView(ActiveFilterView $view, FilterInterface $filter, ActiveFilter $activeFilter, array $options)
    {
        $ids = $activeFilter->getValue();
        $entities = $this->getRepository($options)->findBy(['id' => $ids]);
        $values = [];

        $choiceLabel = $options['entity_label'];
        if (!$choiceLabel instanceof \Closure) {
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
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired('class')
            ->setDefaults([
                'entity_label'  => null,
                'query_builder' => null,
            ])
            ->setAllowedTypes('class', 'string')
            ->setAllowedTypes('entity_label', ['null', 'string', 'closure'])
            ->setAllowedTypes('query_builder', ['null', 'closure']);
    }

    /**
     * @inheritdoc
     */
    public function getParent()
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
    private function getOperators($dataClass, $propertyPath)
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
     * Returns the repository.
     *
     * @param array $options
     *
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    private function getRepository(array $options)
    {
        return $this->registry->getRepository($options['class']);
    }

    /**
     * Returns the property accessor.
     *
     * @return \Symfony\Component\PropertyAccess\PropertyAccessorInterface
     */
    private function getPropertyAccessor()
    {
        if (null !== $this->propertyAccessor) {
            return $this->propertyAccessor;
        }

        return $this->propertyAccessor = PropertyAccess::createPropertyAccessor();
    }
}
