<?php

declare(strict_types=1);

namespace Ekyna\Component\Table\Column;

use Ekyna\Component\Table\Exception;
use Ekyna\Component\Table\Util\Config;

use function array_key_exists;

/**
 * Class ColumnBuilder
 * @package Ekyna\Component\Table\Column
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
final class ColumnBuilder implements ColumnBuilderInterface
{
    private bool                        $locked       = false;
    private string                      $name;
    private ResolvedColumnTypeInterface $type;
    private string                      $label;
    private int                         $position;
    private ?string                     $propertyPath = null;
    private bool                        $sortable;
    private bool                        $exportable;
    private array                       $options;


    /**
     * Constructor.
     *
     * @param string $name
     * @param array  $options
     */
    public function __construct(string $name, array $options = [])
    {
        Config::validateName($name);

        $this->name = $name;
        $this->options = $options;
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
    public function getType(): ResolvedColumnTypeInterface
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
    public function getPosition(): int
    {
        return $this->position;
    }

    /**
     * @inheritDoc
     */
    public function getPropertyPath(): ?string
    {
        return $this->propertyPath;
    }

    /**
     * @inheritDoc
     */
    public function isSortable(): bool
    {
        return $this->sortable;
    }

    /**
     * @inheritDoc
     */
    public function isExportable(): bool
    {
        return $this->exportable;
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
    public function setType(ResolvedColumnTypeInterface $type): ColumnBuilderInterface
    {
        $this->preventIfLocked();

        $this->type = $type;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setLabel(string $label): ColumnBuilderInterface
    {
        $this->preventIfLocked();

        $this->label = $label;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setPosition(int $position): ColumnBuilderInterface
    {
        $this->preventIfLocked();

        $this->position = $position;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setPropertyPath(?string $path): ColumnBuilderInterface
    {
        $this->propertyPath = $path;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setSortable(bool $sortable): ColumnBuilderInterface
    {
        $this->sortable = $sortable;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setExportable(bool $exportable): ColumnBuilderInterface
    {
        $this->exportable = $exportable;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getColumnConfig(): ColumnConfigInterface
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
    public function getColumn(): ColumnInterface
    {
        $this->preventIfLocked();

        return new Column($this->getColumnConfig());
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
