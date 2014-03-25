<?php

namespace Ekyna\Component\Table\View;

class Row
{
    public $entityId;
    public $cells = array();

    public function __construct($entityId)
    {
        $this->entityId = $entityId;
    }
}