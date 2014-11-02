<?php

namespace Ekyna\Component\Table\Extension\Core\Type\Filter;

use Doctrine\ORM\EntityManagerInterface;
use Ekyna\Bundle\CoreBundle\Form\DataTransformer\IdentifierToObjectTransformer;
use Ekyna\Component\Table\AbstractFilterType;
use Ekyna\Component\Table\TableView;
use Ekyna\Component\Table\Util\FilterOperator;
use Ekyna\Component\Table\View\ActiveFilter;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class EntityType
 * @package Ekyna\Component\Table\Extension\Core\Type\Filter
 * @author Étienne Dauvergne <contact@ekyna.com>
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
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolverInterface $resolver)
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefaults(array(
                'class' => null,
            ))
            ->setRequired(array('class'))
            ->setAllowedTypes(array(
                'class' => 'string',
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function buildFilterFrom(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('operator', 'choice', array(
                'label' => false,
                'choices' => FilterOperator::getChoices($this->getOperators())
            ))
            ->add(
                $builder->create('value', 'entity', array(
                    'class' => $options['class'],
                    'multiple' => true,
                ))->addModelTransformer(
                    new IdentifierToObjectTransformer($this->em->getRepository($options['class']))
                )
            );
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function buildActiveFilter(TableView $view, array $datas, array $options)
    {
        $repo = $this->em->getRepository($options['class']);

        $entities = $repo->findBy(array('id' => $datas['value']));
        $values = [];
        foreach($entities as $entity) {
            $values[] = (string) $entity;
        }

        $activeFilter = new ActiveFilter();
        $activeFilter->setVars(array(
            'full_name' => $datas['full_name'],
            'id'        => $datas['id'],
            'field'     => $datas['label'],
            'operator'  => FilterOperator::getLabel($datas['operator']),
            'value'     => implode(', ', $values)
        ));
        $view->active_filters[] = $activeFilter;
    }

    /**
     * {@inheritdoc}
     */
    public function getOperators()
    {
        return array(
            FilterOperator::IN,
            FilterOperator::NOT_IN,
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'entity';
    }
}
