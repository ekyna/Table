<?php

declare(strict_types=1);

namespace Ekyna\Component\Table\Bridge\Symfony\Session;

use Ekyna\Component\Table\Context;
use Ekyna\Component\Table\Exception;
use Ekyna\Component\Table\TableInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use function json_decode;
use function json_encode;

/**
 * Class SessionStorage
 * @package Ekyna\Component\Table\Bridge\Symfony\Session
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class SessionStorage implements Context\Session\StorageInterface
{
    private const PREFIX = 'table_context/';

    private SessionInterface $session;


    /**
     * Constructor.
     *
     * @param SessionInterface $session
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @inheritDoc
     */
    public function load(TableInterface $table): void
    {
        $key = $this->getKey($table);

        if ($this->session->has($key)) {
            try {
                $data = json_decode($this->session->get($key));
                $table->getContext()->fromArray($data);
            } catch (Exception\InvalidArgumentException) {
                $this->clear($table);
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function save(TableInterface $table): void
    {
        if ($table->getContext()->isDefault()) {
            $this->clear($table);

            return;
        }

        // TODO Track if context has changed and if writing session if needed
        $this->session->set($this->getKey($table), json_encode($table->getContext()->toArray()));
    }

    /**
     * @inheritDoc
     */
    public function clear(TableInterface $table): void
    {
        $this->session->remove($this->getKey($table));
    }

    /**
     * Returns the session key for the given table.
     *
     * @param TableInterface $table
     *
     * @return string
     */
    private function getKey(TableInterface $table): string
    {
        return static::PREFIX . $table->getHash();
    }
}
