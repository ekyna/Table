<?php

namespace Ekyna\Component\Table\Source;

/**
 * Interface SourceInterface
 * @package Ekyna\Component\Table\Source
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
interface SourceInterface
{
    /**
     * Returns the FQCN of the adapter factory supporting this source.
     *
     * @return string
     */
    static public function getFactory();
}
