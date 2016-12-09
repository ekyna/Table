<?php

namespace Ekyna\Component\Table\Extension\Core\Type\Filter;

use Doctrine\ORM\EntityManagerInterface;
use Ekyna\Bundle\CoreBundle\Form\DataTransformer\IdentifierToObjectTransformer;
use Ekyna\Component\Table\AbstractFilterType;
use Ekyna\Component\Table\TableView;
use Ekyna\Component\Table\Util\FilterOperator;
use Ekyna\Component\Table\View\ActiveFilter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType as FormEntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * Class EntityType
 * @package Ekyna\Component\Table\Extension\Core\Type\Filter
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class EntityType extends AbstractFilterType
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * Constructor.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'class'         => null,
            'property'      => null,
            'query_builder' => null,
        ]);

        $resolver->setRequired(['class']);

        $resolver->setAllowedTypes('class', 'string');
        $resolver->setAllowedTypes('property', ['null', 'string']);
        $resolver->setAllowedTypes('query_builder', ['null', 'closure']);
    }

    /**
     * @inheritdoc
     */
    public function buildFilterFrom(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('operator', ChoiceType::class, [
                'label'   => false,
                'choices' => FilterOperator::getChoices($this->getOperators()),
            ])
            ->add(
                $builder->create('value', FormEntityType::class, [
                    'label'         => false,
                    'class'         => $options['class'],
                    'multiple'      => true,
                    'choice_label'  => $options['property'],
                    'query_builder' => $options['query_builder'],
                ])->addModelTransformer(
                    new IdentifierToObjectTransformer($this->em->getRepository($options['class']))
                )
            );;
    }

    /**
     * @inheritdoc
     */
    public function buildActiveFilter(TableView $view, array $data, array $options)
    {
        $repo = $this->em->getRepository($options['class']);

        $entities = $repo->findBy(['id' => $data['value']]);
        $values = [];

        if (0 < strlen($property = $options['property'])) {
            $accessor = PropertyAccess::createPropertyAccessor();
            $transform = function ($entity) use ($accessor, $property) {
                return $accessor->getValue($entity, $property);
            };
        } else {
            $transform = function ($entity) {
                return (string)$entity;
            };
        }

        foreach ($entities as $entity) {
            $values[] = $transform($entity);
        }

        $activeFilter = new ActiveFilter();
        $activeFilter->setVars([
            'full_name' => $data['full_name'],
            'id'        => $data['id'],
            'field'     => $data['label'],
            'operator'  => FilterOperator::getLabel($data['operator']),
            'value'     => $values,
        ]);
        $view->active_filters[] = $activeFilter;
    }

    /**
     * @inheritdoc
     */
    public function getOperators()
    {
        return [
            FilterOperator::IN,
            FilterOperator::NOT_IN,
        ];
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'entity';
    }
}
