<?php

namespace Ekyna\Component\Table\Http;

use Ekyna\Component\Table\TableInterface;

/**
 * Interface AdapterInterface
 * @package Ekyna\Component\Table
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
interface AdapterInterface
{
    /**
     * Builds the parameters.
     *
     * @param TableInterface $table
     * @param mixed          $request
     *
     * @return ParametersHelper
     */
    public function loadParameters(TableInterface $table, $request = null);

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
     * @param string $body
     * @param int    $code
     * @param array  $headers
     *
     * @return mixed The response
     */
    public function createResponse($body, $code = 200, array $headers = []);
}
