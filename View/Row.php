<?php

namespace Ekyna\Component\Table\View;

/**
 * Class Row
 * @package Ekyna\Component\Table\View
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class Row
{
    public $entityId;
    public $cells = array();

    public function __construct($entityId)
    {
        $this->entityId = $entityId;
    }
}