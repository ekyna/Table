<?php

declare(strict_types=1);

namespace Ekyna\Component\Table\Bridge\Doctrine\ORM\Form;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\PropertyAccess\PropertyAccess;

use function count;
use function implode;
use function is_array;

/**
 * Class IdToObjectTransformer
 * @package Ekyna\Component\Table\Bridge\Doctrine\ORM\Form
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class IdToObjectTransformer implements DataTransformerInterface
{
    protected ObjectRepository $repository;
    protected string           $identifier;


    /**
     * Constructor.
     *
     * @param ObjectRepository $repository
     * @param string           $identifier
     */
    public function __construct(ObjectRepository $repository, string $identifier = 'id')
    {
        $this->repository = $repository;
        $this->identifier = $identifier;
    }

    /**
     * @inheritDoc
     */
    public function transform($value)
    {
        if (empty($value)) {
            return null;
        }

        if (is_array($value)) {
            if (null === $entities = $this->repository->findBy([$this->identifier => $value])) {
                throw new TransformationFailedException(sprintf(
                    'Objects "%s" could not be converted from value "%".',
                    $this->repository->getClassName(),
                    implode(', ', $value)
                ));
            }

            if (count($entities) !== count($value)) {
                throw new TransformationFailedException(sprintf(
                    'One or more objects "%s" could not be converted from value "%s".',
                    $this->repository->getClassName(),
                    implode(', ', $value)
                ));
            }

            return $entities;
        }

        if (null === $entity = $this->repository->findOneBy([$this->identifier => $value])) {
            throw new TransformationFailedException(sprintf(
                'Object "%s" with id "%s" does not exist.',
                $this->repository->getClassName(),
                $value
            ));
        }

        return $entity;
    }

    /**
     * @inheritDoc
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
                $identifiers[] = $accessor->getValue($entity, $this->identifier);
            }

            return $identifiers;
        }

        if (!$value instanceof $class) {
            throw new UnexpectedTypeException($value, $class);
        }

        return $accessor->getValue($value, $this->identifier);
    }
}
