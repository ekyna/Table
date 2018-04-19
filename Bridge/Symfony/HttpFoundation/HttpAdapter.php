<?php

namespace Ekyna\Component\Table\Bridge\Symfony\HttpFoundation;

use Ekyna\Component\Table\Exception\UnexpectedTypeException;
use Ekyna\Component\Table\Http\AdapterInterface;
use Ekyna\Component\Table\TableInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class HttpAdapter
 * @package Ekyna\Component\Table\Bridge\Symfony\HttpFoundation
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class HttpAdapter implements AdapterInterface
{
    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    /**
     * @var TranslatorInterface
     */
    private $translator;


    /**
     * Constructor.
     *
     * @param FlashBagInterface   $flashBag
     * @param TranslatorInterface $translator
     */
    public function __construct(FlashBagInterface $flashBag, TranslatorInterface $translator)
    {
        $this->flashBag = $flashBag;
        $this->translator = $translator;
    }

    /**
     * @inheritDoc
     */
    public function loadParameters(TableInterface $table, $request = null)
    {
        if (!$request instanceof Request) {
            throw new UnexpectedTypeException($request, Request::class);
        }

        $helper = $table->getParametersHelper();

        if ('POST' === $request->getMethod()) {
            $bag = $request->request;
        } elseif ('GET' === $request->getMethod()) {
            $bag = $request->query;
        } else {
            return $helper;
        }

        $helper->setData((array)$bag->get($table->getName()));

        return $helper;
    }

    /**
     * @inheritdoc
     */
    public function redirect(TableInterface $table)
    {
        foreach ($table->getErrors() as $error) {
            $message = $error->getMessage();
            if (null !== $error->getTranslationDomain()) {
                $message = $this->translator->trans($message, $error->getParameters(), $error->getTranslationDomain());
            }
            $this->addFlash('danger', $message);
        }

        return $this->createRedirection($table->getConfig()->getUrl());
    }

    /**
     * @inheritdoc
     */
    public function addFlash($type, $message)
    {
        $this->flashBag->add($type, $message);
    }

    /**
     * @inheritDoc
     */
    public function createRedirection($url = null)
    {
        return new RedirectResponse($url);
    }

    /**
     * @inheritDoc
     */
    public function createResponse($body, $code = 200, array $headers = [])
    {
        return new Response($body, $code, $headers);
    }
}
