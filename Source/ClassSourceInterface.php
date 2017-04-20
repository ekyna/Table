<?php

declare(strict_types=1);

namespace Ekyna\Component\Table\Source;

/**
 * Interface ClassSourceInterface
 * @package Ekyna\Component\Table\Source
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
interface ClassSourceInterface extends SourceInterface
{
    /**
     * Returns the class of the data source.
     *
     * @return string
     */
    public function getClass(): string;
}
