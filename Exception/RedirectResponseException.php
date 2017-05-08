<?php

namespace Ekyna\Component\Table\Exception;

/**
 * Class RedirectResponseException
 * @package Ekyna\Component\Table\Exception
 * @author  Etienne Dauvergne <contact@ekyna.com>
 * @deprecated
 * @todo remove
 */
class RedirectResponseException extends \Exception implements ExceptionInterface
{
    /**
     * @var string
     */
    private $url;


    /**
     * Constructor.
     *
     * @param string $url
     */
    public function __construct($url = null)
    {
        $this->url = $url;
    }

    /**
     * Returns the url to redirect to.
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }
}
