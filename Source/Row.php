<?php

declare(strict_types=1);

namespace Ekyna\Component\Table\Source;

use Ekyna\Component\Table\Exception\InvalidArgumentException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

/**
 * Class Row
 * @package      Ekyna\Component\Table\Source
 * @author       Etienne Dauvergne <contact@ekyna.com>
 */
class Row implements RowInterface
{
    private string                    $identifier;
    private object                    $data;
    private array                     $extra;
    private PropertyAccessorInterface $propertyAccessor;


    /**
     * Constructor.
     *
     * @param string $identifier
     * @param object $data
     * @param PropertyAccessorInterface|null $propertyAccessor
     */
    public function __construct(
        string $identifier,
        object $data,
        array $extraData,
        PropertyAccessorInterface $propertyAccessor = null
    ) {
        if (('0' !== $identifier) && empty($identifier)) {
            throw new InvalidArgumentException('Empty identifier.');
        }

        $this->identifier = $identifier;
        $this->data = $data;
        $this->extra = $extraData;
        $this->propertyAccessor = $propertyAccessor ?: PropertyAccess::createPropertyAccessor();
    }

    /**
     * @inheritDoc
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * @inheritDoc
     */
    public function getData(?string $propertyPath): mixed
    {
        if (is_null($propertyPath)) {
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
    public function getExtra(?string $name): mixed
    {
        if (is_null($name)) {
            return $this->extra;
        }

        return $this->extra[$name] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function getPropertyAccessor(): PropertyAccessorInterface
    {
        return $this->propertyAccessor;
    }
}
