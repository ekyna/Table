<?php

namespace Ekyna\Component\Table\Extension\Core\Type\Filter;

use Doctrine\ORM\EntityManagerInterface;
use Ekyna\Bundle\CoreBundle\Form\DataTransformer\IdentifierToObjectTransformer;
use Ekyna\Component\Table\AbstractFilterType;
use Ekyna\Component\Table\TableView;
use Ekyna\Component\Table\Util\FilterOperator;
use Ekyna\Component\Table\View\ActiveFilter;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * Class ChoiceType
 * @package Ekyna\Component\Table\Extension\Core\Type\Filter
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class ChoiceType extends AbstractFilterType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefaults([
                'choices' => null,
            ])
            ->setRequired(['choices'])
            ->setAllowedTypes([
                'choices' => 'array',
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function buildFilterFrom(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('operator', 'choice', [
                'label' => false,
                'choices' => FilterOperator::getChoices($this->getOperators())
            ])
            ->add(
                $builder->create('value', 'choice', [
                    'label'    => false,
                    'multiple' => true,
                    'choices'  => $options['choices'],
                ])
            );
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function buildActiveFilter(TableView $view, array $data, array $options)
    {
        $value = $data['value'];
        $choices = $options['choices'];
        $transform = function($v) use ($choices) {
            if (array_key_exists($v, $choices)) {
                return $choices[$v];
            }
            return $v;
        };

        if (is_array($value)) {
            $value = array_map($transform, $value);
        } else {
            $value = $transform($value);
        }

        $activeFilter = new ActiveFilter();
        $activeFilter->setVars([
            'full_name' => $data['full_name'],
            'id'        => $data['id'],
            'field'     => $data['label'],
            'operator'  => FilterOperator::getLabel($data['operator']),
            'value'     => $value,
        ]);
        $view->active_filters[] = $activeFilter;
    }

    /**
     * {@inheritdoc}
     */
    public function getOperators()
    {
        return [
            FilterOperator::IN,
            FilterOperator::NOT_IN,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'choice';
    }
}
