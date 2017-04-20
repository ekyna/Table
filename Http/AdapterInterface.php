<?php

declare(strict_types=1);

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
     * @param object|null    $request
     *
     * @return ParametersHelper
     */
    public function loadParameters(TableInterface $table, object $request = null): ParametersHelper;

    /**
     * Creates and returns a redirection from the given table.
     *
     * @param TableInterface $table
     *
     * @return object
     */
    public function redirect(TableInterface $table): object;

    /**
     * Adds the flash message to the session.
     *
     * @param string $type
     * @param string $message
     */
    public function addFlash(string $type, string $message): void;

    /**
     * Creates and returns a redirection.
     *
     * @param string|null $url
     *
     * @return object The redirection response
     */
    public function createRedirection(string $url = null): object;

    /**
     * Creates and returns a new response.
     *
     * @param string $body
     * @param int    $code
     * @param array  $headers
     *
     * @return object The response
     */
    public function createResponse(string $body, int $code = 200, array $headers = []): object;
}
