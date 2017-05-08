<?php

namespace Ekyna\Component\Table\Http;

use Ekyna\Component\Table\Context\ContextInterface;
use Ekyna\Component\Table\TableInterface;

/**
 * Interface RequestHandlerInterface
 * @package Ekyna\Component\Table
 * @author  Etienne Dauvergne <contact@ekyna.com>
 * @deprecated
 */
interface RequestHandlerInterface
{
    /**
     * Handles the request.
     *
     * @param TableInterface $table
     * @param mixed          $request
     *
     * @return mixed The response if any
     */
    public function handleRequest(TableInterface $table, $request = null);

    /**
     * Creates and returns a redirection.
     *
     * @param string|null $url
     *
     * @return mixed The redirection response
     */
    public function createRedirection($url = null);

    /**
     * Creates and returns a new response.
     *
     * @param       $body
     * @param int   $code
     * @param array $headers
     *
     * @return mixed The response
     */
    public function createResponse($body, $code = 200, array $headers = []);

    /**
     * Returns the built context.
     *
     * @return ContextInterface
     */
    public function getContext();
}
