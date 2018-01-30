<?php

namespace Ekyna\Component\Table;

/**
 * Class TableError
 * @package Ekyna\Component\Table
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class TableError
{
    /**
     * @var string
     */
    private $message;

    /**
     * @var array
     */
    private $parameters;

    /**
     * @var null|string
     */
    private $translationDomain;


    /**
     * Constructor.
     *
     * @param string $message
     * @param array  $parameters
     * @param string $translationDomain
     */
    public function __construct($message, $parameters = [], $translationDomain = null)
    {
        $this->message = $message;
        $this->parameters = $parameters;
        $this->translationDomain = $translationDomain;
    }

    /**
     * Returns the message.
     *
     * @return string
     */
    public function getMessage()
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
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Returns the parameters.
     *
     * @return array
     */
    public function getParameters()
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
    public function setParameters(array $parameters = [])
    {
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * Returns the translation domain.
     *
     * @return null|string
     */
    public function getTranslationDomain()
    {
        return $this->translationDomain;
    }

    /**
     * Sets the translation domain.
     *
     * @param null|string $domain
     *
     * @return TableError
     */
    public function setTranslationDomain($domain = null)
    {
        $this->translationDomain = $domain;

        return $this;
    }
}