<?php

namespace Ekyna\Component\Table;

/**
 * Class TableView
 * @package Ekyna\Component\Table
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class TableView
{
    public $name = null;
    public $options = [];
    public $attr = [];

    public $available_filters = [];
    public $active_filters = [];

    public $filter_label = null;
    public $filter_form = null;

    public $selection_form = false;

    public $columns = [];
    public $rows = [];

    public $pager = null;
}
