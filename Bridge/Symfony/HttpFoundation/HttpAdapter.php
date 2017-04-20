<?php

declare(strict_types=1);

namespace Ekyna\Component\Table\Bridge\Symfony\HttpFoundation;

use Ekyna\Component\Table\Exception\UnexpectedTypeException;
use Ekyna\Component\Table\Http\AdapterInterface;
use Ekyna\Component\Table\Http\ParametersHelper;
use Ekyna\Component\Table\TableInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class HttpAdapter
 * @package Ekyna\Component\Table\Bridge\Symfony\HttpFoundation
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class HttpAdapter implements AdapterInterface
{
    private FlashBagInterface   $flashBag;
    private TranslatorInterface $translator;


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
    public function loadParameters(TableInterface $table, object $request = null): ParametersHelper
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
     * @inheritDoc
     */
    public function redirect(TableInterface $table): object
    {
        foreach ($table->getErrors() as $error) {
            $message = $error->getMessage();
            if (null !== $error->getTransDomain()) {
                $message = $this->translator->trans($message, $error->getParameters(), $error->getTransDomain());
            }
            $this->addFlash('danger', $message);
        }

        // TODO Page num is neither initialized or passed through batch form action
        $url = $table->getConfig()->getUrl();
        $helper = $table->getParametersHelper();

        if ($helper->getPageValue()) {
            $url .= '?' . $helper->getPageName() . '=' . $helper->getPageValue();
        }

        return $this->createRedirection($url);
    }

    /**
     * @inheritDoc
     */
    public function addFlash($type, $message): void
    {
        $this->flashBag->add($type, $message);
    }

    /**
     * @inheritDoc
     */
    public function createRedirection($url = null): object
    {
        return new RedirectResponse($url);
    }

    /**
     * @inheritDoc
     */
    public function createResponse($body, $code = 200, array $headers = []): object
    {
        return new Response($body, $code, $headers);
    }
}
