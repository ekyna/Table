<?php

namespace Ekyna\Component\Table\Source;

use Ekyna\Component\Table\Exception\InvalidArgumentException;
use Ekyna\Component\Table\Exception\UnexpectedTypeException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

/**
 * Class Row
 * @package Ekyna\Component\Table\Source
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class Row implements RowInterface
{
    /**
     * @var PropertyAccessorInterface
     */
    private $propertyAccessor;

    /**
     * @var string
     */
    private $identifier;

    /**
     * @var mixed
     */
    private $data;


    /**
     * Constructor.
     *
     * @param string                    $identifier
     * @param object                    $data
     * @param PropertyAccessorInterface $propertyAccessor
     */
    public function __construct($identifier, $data, PropertyAccessorInterface $propertyAccessor = null)
    {
        if ('0' !== $identifier && empty($identifier)) {
            throw new InvalidArgumentException('Empty identifier.');
        }

        if (!is_object($data)) {
            throw new UnexpectedTypeException($data, 'object');
        }

        $this->identifier = $identifier;
        $this->data = $data;
        $this->propertyAccessor = $propertyAccessor ?: PropertyAccess::createPropertyAccessor();
    }

    /**
     * @inheritDoc
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @inheritDoc
     */
    public function getData($propertyPath = false)
    {
        if (false === $propertyPath) {
            return $this->data;
        }

        if ($this->propertyAccessor->isReadable($this->data, $propertyPath)) {
            return $this->propertyAccessor->getValue($this->data, $propertyPath);
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function getPropertyAccessor()
    {
        return $this->propertyAccessor;
    }
}
