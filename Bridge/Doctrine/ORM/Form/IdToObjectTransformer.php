<?php

namespace Ekyna\Component\Table\Bridge\Doctrine\ORM\Form;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectRepository;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * Class IdToObjectTransformer
 * @package Ekyna\Component\Table\Bridge\Doctrine\ORM\Form
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class IdToObjectTransformer implements DataTransformerInterface
{
    /**
     * Repository
     *
     * @var ObjectRepository
     */
    protected $repository;


    /**
     * Constructor.
     *
     * @param ObjectRepository $repository
     */
    public function __construct(ObjectRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * {@inheritdoc}
     */
    public function transform($value)
    {
        if (empty($value)) {
            return null;
        }

        if (is_array($value)) {
            if (null === $entities = $this->repository->findBy(['id' => $value])) {
                throw new TransformationFailedException(sprintf(
                    'Objects "%s" could not be converted from value "%".',
                    $this->repository->getClassName(),
                    implode(', ', $value)
                ));
            } elseif (count($entities) !== count($value)) {
                throw new TransformationFailedException(sprintf(
                    'One or more objects "%s" could not be converted from value "%s".',
                    $this->repository->getClassName(),
                    implode(', ', $value)
                ));
            } else {
                return $entities;
            }
        } elseif (null === $entity = $this->repository->findOneBy(['id' => $value])) {
            throw new TransformationFailedException(sprintf(
                'Object "%s" with id "%s" does not exist.',
                $this->repository->getClassName(),
                $value
            ));
        }

        return $entity;
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($value)
    {
        if (null === $value) {
            return '';
        }

        $class = $this->repository->getClassName();
        $accessor = PropertyAccess::createPropertyAccessor();

        if ($value instanceof ArrayCollection) {
            $value = $value->toArray();
        }

        if (is_array($value)) {
            $identifiers = [];
            foreach ($value as $entity) {
                if (!$entity instanceof $class) {
                    throw new UnexpectedTypeException($entity, $class);
                }
                $identifiers[] = $accessor->getValue($entity, 'id');
            }

            return $identifiers;
        } elseif (!$value instanceof $class) {
            throw new UnexpectedTypeException($value, $class);
        }

        return $accessor->getValue($value, 'id');
    }
}
