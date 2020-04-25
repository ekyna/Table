<?php

namespace Ekyna\Component\Table\Http;

use Ekyna\Component\Table\TableInterface;

/**
 * Class RequestHandler
 * @package Ekyna\Component\Table\Extension\HttpFoundation
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class RequestHandler
{
    /**
     * @var TableInterface
     */
    private $table;


    /**
     * Constructor.
     *
     * @param TableInterface $table
     */
    public function __construct(TableInterface $table)
    {
        $this->table = $table;
    }

    /**
     * @inheritDoc
     */
    public function handleRequest($request = null)
    {
        $config = $this->table->getConfig();

        // Load from the session
        $session = $config->getSessionStorage();
        $session->load($this->table);

        // Load parameters from the request
        $parameters = $config->getHttpAdapter()->loadParameters($this->table, $request);

        // Abort if request is empty
        if ($parameters->isDefault()) {
            return null;
        }

        $response = null;

        // Execute parameters handlers
        $classes = [
            Handler\LoadHandler::class,
            Handler\ProfileHandler::class,
            Handler\SortHandler::class,
            Handler\FilterHandler::class,
            Handler\BatchHandler::class,
            Handler\ExportHandler::class,
        ];
        foreach ($classes as $class) {
            /** @var Handler\HandlerInterface $handler */
            $handler = new $class;

            // Abort on response
            if (null !== $response = $handler->execute($this->table, $request)) {
                break;
            }
        }

        // Saves the context
        $session->save($this->table);

        return $response;
    }
}
