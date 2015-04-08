<?php

namespace Ekyna\Component\Table\Extension\Templating;

use Ekyna\Component\Table\AbstractTableExtension;
use Ekyna\Component\Table\TableRenderer;
use Ekyna\Component\Table\Templating\TableHelper;
use Symfony\Component\Templating\PhpEngine;

/**
 * Integrates the Templating component with the Form library.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class TemplatingExtension extends AbstractTableExtension
{
    public function __construct(PhpEngine $engine, array $defaultThemes = array())
    {
        $engine->addHelpers(array(
            new TableHelper(new TableRenderer(new TemplatingRendererEngine($engine, $defaultThemes))),
        ));
    }
}
