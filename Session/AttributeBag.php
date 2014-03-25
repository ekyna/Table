<?php

namespace Ekyna\Component\Table\Session;

use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag as BaseAttributeBag;

class AttributeBag extends BaseAttributeBag
{
    /**
     * Constructor.
     *
     * @param string $storageKey The key used to store attributes in the session
     */
    public function __construct($name = 'ekyna_table_attributes')
    {
        $this->storageKey = '_'.$name;
        $this->setName($name);
    }
}