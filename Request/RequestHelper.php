<?php

namespace Ekyna\Component\Table\Request;

use Symfony\Component\HttpFoundation\Request;

/**
 * Class RequestHelper
 * @package Ekyna\Component\Table\Request
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class RequestHelper
{
    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    private $request;

    /**
     * @var \Symfony\Component\HttpFoundation\Session\Session
     */
    private $session;

    /**
     * Sets the request.
     *
     * @param Request $request
     */
    public function setRequest(Request $request = null)
    {
        $this->request = $request;
        if ($request instanceof Request) {
            $this->session = $request->getSession();
        }
    }

    /**
     * Returns the request.
     *
     * @return \Symfony\Component\HttpFoundation\Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Returns a user var from the request.
     *
     * @param string $name
     * @param mixed $default
     *
     * @return mixed
     */
    public function getRequestVar($name, $default = null)
    {
        if (null === $this->request) {
            return $default;
        }
        $value = $default;
        if(null !== $query = $this->request->query->get($name)) {
            $value = $query;
        }elseif(null !== $request = $this->request->request->get($name)) {
            $value = $request;
        }
        return $value;
    }

    /**
     * Returns a user var from the session.
     *
     * @param string $name
     * @param null $default
     *
     * @return mixed
     */
    public function getSessionVar($name, $default = null)
    {
        if (null === $this->session) {
            return $default;
        }
        return $this->session->get($name, $default);
    }

    /**
     * Returns a user var from request or session.
     *
     * @param string $name
     * @param null $default
     *
     * @return mixed
     */
    public function getVar($name, $default = null)
    {
        $value = $this->getSessionVar($name, $default);
        if(null !== $request = $this->getRequestVar($name)) {
            $value = $request;
        }
        if($value !== $default) {
            $this->setVar($name, $value);
        }
        return $value;
    }

    /**
     * Stores a variable in the session.
     *
     * @param string $name
     * @param mixed $value
     */
    public function setVar($name, $value)
    {
        if (null === $this->session) {
            return;
        }
        $this->session->set($name, $value);
    }

    /**
     * Removes a variable from the session.
     *
     * @param $name
     */
    public function unsetVar($name)
    {
        if (null === $this->session) {
            return;
        }
        $this->session->remove($name);
    }
}
