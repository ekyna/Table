<?php

declare(strict_types=1);

namespace Ekyna\Component\Table\Action;

use Ekyna\Component\Table\Exception\LogicException;
use Ekyna\Component\Table\Exception\UnexpectedTypeException;
use Ekyna\Component\Table\Extension\ActionTypeExtensionInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ResolvedActionType
 * @package Ekyna\Component\Table\Action
 * @author  Etienne Dauvergne <contact@ekyna.com>
 *
 * @property ActionTypeExtensionInterface[] $typeExtensions
 */
final class ResolvedActionType implements ResolvedActionTypeInterface
{
    private ActionTypeInterface          $innerType;
    private array                        $typeExtensions;
    private ?ResolvedActionTypeInterface $parent;
    private ?OptionsResolver             $optionsResolver = null;


    /**
     * Constructor.
     *
     * @param ActionTypeInterface              $innerType
     * @param array                            $typeExtensions
     * @param ResolvedActionTypeInterface|null $parent
     */
    public function __construct(
        ActionTypeInterface $innerType,
        array $typeExtensions = [],
        ResolvedActionTypeInterface $parent = null
    ) {
        foreach ($typeExtensions as $extension) {
            if (!$extension instanceof ActionTypeExtensionInterface) {
                throw new UnexpectedTypeException($extension, ActionTypeExtensionInterface::class);
            }
        }

        $this->innerType = $innerType;
        $this->typeExtensions = $typeExtensions;
        $this->parent = $parent;
    }

    /**
     * @inheritDoc
     */
    public function getParent(): ?ResolvedActionTypeInterface
    {
        return $this->parent;
    }

    /**
     * @inheritDoc
     */
    public function getInnerType(): ActionTypeInterface
    {
        return $this->innerType;
    }

    /**
     * @inheritDoc
     */
    public function getTypeExtensions(): array
    {
        return $this->typeExtensions;
    }

    /**
     * @inheritDoc
     */
    public function createBuilder($name, array $options = []): ActionBuilderInterface
    {
        $options = $this->getOptionsResolver()->resolve($options);

        $builder = new ActionBuilder($name, $options);
        $builder->setType($this);

        return $builder;
    }

    /**
     * @inheritDoc
     */
    public function buildAction(ActionBuilderInterface $builder, array $options): void
    {
        $this->parent?->buildAction($builder, $options);

        $this->innerType->buildAction($builder, $options);

        foreach ($this->typeExtensions as $extension) {
            $extension->buildAction($builder, $options);
        }
    }

    /**
     * @inheritDoc
     */
    public function execute(ActionInterface $action, array $options): object|bool
    {
        foreach ($this->typeExtensions as $extension) {
            if (false !== $response = $extension->execute($action, $options)) {
                return $response;
            }
        }

        if (false !== $response = $this->innerType->execute($action, $options)) {
            return $response;
        }

        if (false !== $this->parent) {
            return $this->parent->execute($action, $options);
        }

        throw new LogicException(
            "None of the extensions, type or parent type were able to execute the action.\n" .
            "Did you forget to return true in some 'execute' methods ?"
        );
    }

    /**
     * @inheritDoc
     */
    public function getOptionsResolver(): OptionsResolver
    {
        if (null === $this->optionsResolver) {
            if (null !== $this->parent) {
                $this->optionsResolver = clone $this->parent->getOptionsResolver();
            } else {
                $this->optionsResolver = new OptionsResolver();
            }

            $this->innerType->configureOptions($this->optionsResolver);

            foreach ($this->typeExtensions as $extension) {
                $extension->configureOptions($this->optionsResolver);
            }
        }

        return $this->optionsResolver;
    }
}
