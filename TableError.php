<?php

declare(strict_types=1);

namespace Ekyna\Component\Table;

/**
 * Class TableError
 * @package Ekyna\Component\Table
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class TableError
{
    private string  $message;
    private array   $parameters;
    private ?string $transDomain;


    /**
     * Constructor.
     *
     * @param string      $message
     * @param array       $parameters
     * @param string|null $transDomain
     */
    public function __construct(string $message, array $parameters = [], string $transDomain = null)
    {
        $this->message = $message;
        $this->parameters = $parameters;
        $this->transDomain = $transDomain;
    }

    /**
     * Returns the message.
     *
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * Sets the message.
     *
     * @param string $message
     *
     * @return TableError
     */
    public function setMessage(string $message): TableError
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Returns the parameters.
     *
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * Sets the parameters.
     *
     * @param array $parameters
     *
     * @return TableError
     */
    public function setParameters(array $parameters = []): TableError
    {
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * Returns the translation domain.
     *
     * @return string|null
     */
    public function getTransDomain(): ?string
    {
        return $this->transDomain;
    }

    /**
     * Sets the translation domain.
     *
     * @param string|null $domain
     *
     * @return TableError
     */
    public function setTransDomain(string $domain = null): TableError
    {
        $this->transDomain = $domain;

        return $this;
    }
}
