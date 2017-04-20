<?php

declare(strict_types=1);

namespace Ekyna\Component\Table\Filter;

use Ekyna\Component\Table\Exception;
use Ekyna\Component\Table\Util\Config;
use Symfony\Component\Form\FormFactoryInterface;

use function array_key_exists;

/**
 * Class FilterBuilder
 * @package Ekyna\Component\Table\Filter
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
final class FilterBuilder implements FilterBuilderInterface
{
    private bool $locked = false;
    private string $name;
    private FormFactoryInterface $formFactory;
    private ResolvedFilterTypeInterface $type;
    private string $label;
    private int $position;
    private string $propertyPath;
    private array $options;


    /**
     * Constructor.
     *
     * @param string               $name
     * @param FormFactoryInterface $formFactory
     * @param array                $options
     */
    public function __construct(string $name, FormFactoryInterface $formFactory, array $options = [])
    {
        Config::validateName($name);

        $this->name = $name;
        $this->options = $options;

        $this->setFormFactory($formFactory);
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function getType(): ResolvedFilterTypeInterface
    {
        return $this->type;
    }

    /**
     * @inheritDoc
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @inheritDoc
     */
    public function getFormFactory(): FormFactoryInterface
    {
        return $this->formFactory;
    }

    /**
     * @inheritDoc
     */
    public function getPosition(): int
    {
        return $this->position;
    }

    /**
     * @inheritDoc
     */
    public function getPropertyPath(): string
    {
        return $this->propertyPath;
    }

    /**
     * @inheritDoc
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @inheritDoc
     */
    public function hasOption(string $name): bool
    {
        return array_key_exists($name, $this->options);
    }

    /**
     * @inheritDoc
     */
    public function getOption(string $name, $default = null)
    {
        return array_key_exists($name, $this->options) ? $this->options[$name] : $default;
    }

    /**
     * @inheritDoc
     */
    public function setType(ResolvedFilterTypeInterface $type): FilterBuilderInterface
    {
        $this->preventIfLocked();

        $this->type = $type;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setLabel(string $label): FilterBuilderInterface
    {
        $this->preventIfLocked();

        $this->label = $label;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setFormFactory(FormFactoryInterface $formFactory): FilterBuilderInterface
    {
        $this->preventIfLocked();

        $this->formFactory = $formFactory;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setPosition(int $position): FilterBuilderInterface
    {
        $this->preventIfLocked();

        $this->position = $position;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setPropertyPath(string $path): FilterBuilderInterface
    {
        $this->preventIfLocked();

        $this->propertyPath = $path;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getFilterConfig(): FilterConfigInterface
    {
        $this->preventIfLocked();

        // This method should be idempotent, so clone the builder
        $config = clone $this;
        $config->locked = true;

        return $config;
    }

    /**
     * @inheritDoc
     */
    public function getFilter(): FilterInterface
    {
        $this->preventIfLocked();

        return new Filter($this->getFilterConfig());
    }

    /**
     * Prevents method call if the config builder is locked.
     *
     * @throws Exception\BadMethodCallException
     */
    private function preventIfLocked(): void
    {
        if ($this->locked) {
            throw new Exception\BadMethodCallException('The config builder cannot be modified anymore.');
        }
    }
}
