<?php

namespace Ekyna\Component\Table;

class TableView
{
    public $name = null;
    public $options = array();
    public $attr = array();

    public $available_filters = array();
    public $active_filters = array();

    public $filter_label = null;
    public $filter_form = null;

    public $selection_form = false;

    public $columns = array();
    public $rows = array();

    public $pager = null;
}
