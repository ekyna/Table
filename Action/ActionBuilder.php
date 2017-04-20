<?php

declare(strict_types=1);

namespace Ekyna\Component\Table\Action;

use Ekyna\Component\Table\Exception;
use Ekyna\Component\Table\Util\Config;

use function array_key_exists;

/**
 * Class ActionBuilder
 * @package Ekyna\Component\Table\Action
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
final class ActionBuilder implements ActionBuilderInterface
{
    private string                      $name;
    private string                      $label;
    private ResolvedActionTypeInterface $type;
    private array                       $options;
    private bool                        $locked = false;


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
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @inheritDoc
     */
    public function getType(): ResolvedActionTypeInterface
    {
        return $this->type;
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
    public function setType(ResolvedActionTypeInterface $type): ActionBuilderInterface
    {
        $this->preventIfLocked();

        $this->type = $type;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setLabel(string $label): ActionBuilderInterface
    {
        $this->preventIfLocked();

        $this->label = $label;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getActionConfig(): ActionConfigInterface
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
    public function getAction(): ActionInterface
    {
        $this->preventIfLocked();

        return new Action($this->getActionConfig());
    }

    /**
     * Prevents method call if the config builder is locked.
     *
     * @throws Exception\BadMethodCallException
     */
    private function preventIfLocked(): void
    {
        if (!$this->locked) {
            return;
        }

        throw new Exception\BadMethodCallException('The action builder cannot be modified anymore.');
    }
}
