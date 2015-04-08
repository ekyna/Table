<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license intableation, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ekyna\Component\Table\Twig\Renderer;

use Ekyna\Component\Table\TableRendererEngineInterface;

/**
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
interface TwigRendererEngineInterface extends TableRendererEngineInterface
{
    /**
     * Sets Twig's environment.
     *
     * @param \Twig_Environment $environment
     */
    public function setEnvironment(\Twig_Environment $environment);
}
