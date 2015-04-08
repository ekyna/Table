<?php

namespace Ekyna\Component\Table\Twig\Renderer;

use Ekyna\Component\Table\Exception\UnexpectedTypeException;
use Ekyna\Component\Table\TableRenderer;

/**
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class TwigRenderer extends TableRenderer implements TwigRendererInterface
{
    /**
     * @var TwigRendererEngineInterface
     */
    private $engine;

    public function __construct(TwigRendererEngineInterface $engine)
    {
        parent::__construct($engine);

        $this->engine = $engine;
    }

    /**
     * {@inheritdoc}
     */
    public function setEnvironment(\Twig_Environment $environment)
    {
        $this->engine->setEnvironment($environment);
    }
}
