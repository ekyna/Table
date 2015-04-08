<?php

namespace Ekyna\Component\Table\Twig\Renderer;

use Ekyna\Component\Table\TableRendererInterface;

/**
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
interface TwigRendererInterface extends TableRendererInterface
{
    /**
     * Sets Twig's environment.
     *
     * @param \Twig_Environment $environment
     */
    public function setEnvironment(\Twig_Environment $environment);
}
