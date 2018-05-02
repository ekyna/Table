<?php

namespace Ekyna\Component\Table\Http;

use Ekyna\Component\Table\Column\ColumnInterface;
use Ekyna\Component\Table\Context\ActiveFilter;
use Ekyna\Component\Table\Filter\FilterInterface;
use Ekyna\Component\Table\Util\ColumnSort;
use Ekyna\Component\Table\Util\Config;

/**
 * Class ParametersHelper
 * @package Ekyna\Component\Table\Request
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class ParametersHelper
{
    const SORT            = 'sort';
    const PAGE_NUM        = 'page';
    const ADD_FILTER      = 'add_filter';
    const REMOVE_FILTER   = 'remove_filter';
    const IDENTIFIERS     = 'identifiers';
    const ALL             = 'all';
    const BATCH           = 'batch';
    const ACTION          = 'action';
    const EXPORT          = 'export';
    const FORMAT          = 'format';
    const CONFIG          = 'config';
    const VISIBLE_COLUMNS = 'columns';
    const MAX_PER_PAGE    = 'page_max';
    const PROFILE         = 'profile';


    /**
     * @var string
     */
    private $tableName;

    /**
     * @var bool
     */
    private $selectionMode;

    /**
     * @var bool
     */
    private $default;

    /**
     * @var array
     */
    private $data;


    /**
     * Constructor.
     *
     * @param string $tableName
     * @param string $selectionMode
     */
    public function __construct($tableName, $selectionMode)
    {
        $this->tableName = $tableName;
        $this->selectionMode = $selectionMode;

        $this->setData();
    }

    /**
     * Sets the data.
     *
     * @param array $data
     */
    public function setData(array $data = [])
    {
        $this->default = empty($data);

        $this->data = array_replace([
            static::VISIBLE_COLUMNS => [],
            static::MAX_PER_PAGE    => null,
            static::CONFIG          => null,
            static::PAGE_NUM        => null,
            static::SORT            => null,
            static::ADD_FILTER      => null,
            static::REMOVE_FILTER   => null,
            static::IDENTIFIERS     => [],
            static::ALL             => null,
            static::BATCH           => null,
            static::ACTION          => null,
            static::EXPORT          => null,
            static::FORMAT          => null,
            static::PROFILE         => [],
        ], $data);
    }

    /**
     * Returns whether the parameters values are the default ones.
     *
     * @return bool
     */
    public function isDefault()
    {
        return $this->default;
    }

    /**
     * Returns the columns parameter name.
     *
     * @return string
     */
    public function getColumnsName()
    {
        return $this->tableName . '[' . static::VISIBLE_COLUMNS . '][]';
    }

    /**
     * Returns the columns parameter value.
     *
     * @return array
     */
    public function getColumnsValue()
    {
        return (array)$this->data[static::VISIBLE_COLUMNS];
    }

    /**
     * Returns the "max per page" parameter name.
     *
     * @return string
     */
    public function getMaxPerPageName()
    {
        return $this->tableName . '[' . static::MAX_PER_PAGE . ']';
    }

    /**
     * Returns the "max per page" parameter value.
     *
     * @return string|null
     */
    public function getMaxPerPageValue()
    {
        return $this->data[static::MAX_PER_PAGE];
    }

    /**
     * Returns the config button name.
     *
     * @return string
     */
    public function getConfigButton()
    {
        return $this->tableName . '[' . static::CONFIG . ']';
    }

    /**
     * Returns whether the config button is clicked.
     *
     * @return bool
     */
    public function isConfigClicked()
    {
        return $this->data[static::CONFIG] === static::CONFIG;
    }

    /**
     * Returns the page parameter name.
     *
     * @return string
     */
    public function getPageName()
    {
        return $this->tableName . '[' . static::PAGE_NUM . ']';
    }

    /**
     * Returns the page parameter value.
     *
     * @return int
     */
    public function getPageValue()
    {
        return (int)$this->data[static::PAGE_NUM];
    }

    /**
     * Returns the sort parameter name.
     *
     * @return string
     */
    public function getSortName()
    {
        return $this->tableName . '[' . static::SORT . ']';
    }

    /**
     * Returns the sort parameter name.
     *
     * @return array|null
     */
    public function getSortValue()
    {
        return $this->data[static::SORT];
    }

    /**
     * Returns the sort column href.
     *
     * @param ColumnInterface $column
     *
     * @return string
     */
    public function getSortHref(ColumnInterface $column)
    {
        $parameter = $this->getSortName();
        $dir = ($column->getSortDirection() === ColumnSort::ASC ? ColumnSort::DESC : ColumnSort::ASC);

        return '?' . $parameter . '[by]=' . $column->getName() . '&' . $parameter . '[dir]=' . $dir;
    }

    /**
     * Returns the add filter parameter value.
     *
     * @return string
     */
    public function getAddFilterName()
    {
        return $this->tableName . '[' . static::ADD_FILTER . ']';
    }

    /**
     * Returns the add filter parameter name.
     *
     * @return string|null
     */
    public function getAddFilterValue()
    {
        return $this->data[static::ADD_FILTER];
    }

    /**
     * Returns the add filter href.
     *
     * @param FilterInterface $filter
     *
     * @return string
     */
    public function getAddFilterHref(FilterInterface $filter)
    {
        return '?' . $this->getAddFilterName() . '=' . $filter->getName();
    }

    /**
     * Returns the remove filter parameter name.
     *
     * @return string
     */
    public function getRemoveFilterName()
    {
        return $this->tableName . '[' . static::REMOVE_FILTER . ']';
    }

    /**
     * Returns the remove filter parameter value.
     *
     * @return string|null
     */
    public function getRemoveFilterValue()
    {
        return $this->data[static::REMOVE_FILTER];
    }

    /**
     * Returns the remove filter href.
     *
     * @param ActiveFilter $filter
     *
     * @return string
     */
    public function getRemoveFilterHref(ActiveFilter $filter)
    {
        return '?' . $this->getRemoveFilterName() . '=' . $filter->getId();
    }

    /**
     * Returns the identifiers parameter name.
     *
     * @return string
     */
    public function getIdentifiersName()
    {
        $name = $this->tableName . '[' . static::IDENTIFIERS . ']';

        if ($this->selectionMode == Config::SELECTION_MULTIPLE) {
            $name .= '[]';
        }

        return $name;
    }

    /**
     * Returns the identifiers parameter value.
     *
     * @return array
     */
    public function getIdentifiersValue()
    {
        return (array)$this->data[static::IDENTIFIERS];
    }

    /**
     * Returns the "all elements" parameter name.
     *
     * @return string
     */
    public function getAllName()
    {
        return $this->tableName . '[' . static::ALL . ']';
    }

    /**
     * Returns the "all elements" parameter value.
     *
     * @return bool
     */
    public function getAllValue()
    {
        return (bool)$this->data[static::ALL];
    }

    /**
     * Returns the batch button name.
     *
     * @return string
     */
    public function getBatchButton()
    {
        return $this->tableName . '[' . static::BATCH . ']';
    }

    /**
     * Returns whether the batch button is clicked.
     *
     * @return bool
     */
    public function isBatchClicked()
    {
        return $this->data[static::BATCH] === static::BATCH;
    }

    /**
     * Returns the action parameter name.
     *
     * @return string
     */
    public function getActionName()
    {
        return $this->tableName . '[' . static::ACTION . ']';
    }

    /**
     * Returns the action parameter value.
     *
     * @return string|null
     */
    public function getActionValue()
    {
        return $this->data[static::ACTION];
    }

    /**
     * Returns the export button name.
     *
     * @return string
     */
    public function getExportButton()
    {
        return $this->tableName . '[' . static::EXPORT . ']';
    }

    /**
     * Returns whether the export button is clicked.
     *
     * @return bool
     */
    public function isExportClicked()
    {
        return $this->data[static::EXPORT] === static::EXPORT;
    }

    /**
     * Returns the format parameter name.
     *
     * @return string
     */
    public function getFormatName()
    {
        return $this->tableName . '[' . static::FORMAT . ']';
    }

    /**
     * Returns the format parameter value.
     *
     * @return string|null
     */
    public function getFormatValue()
    {
        return strtolower($this->data[static::FORMAT]);
    }


    /**
     * Returns the profile choice parameter name.
     *
     * @return string
     */
    public function getProfileChoiceName()
    {
        return $this->getProfileName('choice');
    }

    /**
     * Returns the profile choice parameter value.
     *
     * @return string|null
     */
    public function getProfileChoiceValue()
    {
        return $this->getProfileValue('choice');
    }

    /**
     * Returns the profile load button name.
     *
     * @return string
     */
    public function getProfileLoadButton()
    {
        return $this->getProfileName('load');
    }

    /**
     * Returns whether the profile load button is clicked.
     *
     * @return bool
     */
    public function isProfileLoadClicked()
    {
        return $this->getProfileValue('load') === 'load';
    }

    /**
     * Returns the profile edit button name.
     *
     * @return string
     */
    public function getProfileEditButton()
    {
        return $this->getProfileName('edit');
    }

    /**
     * Returns whether the profile edit button is clicked.
     *
     * @return bool
     */
    public function isProfileEditClicked()
    {
        return $this->getProfileValue('edit') === 'edit';
    }

    /**
     * Returns the profile remove button name.
     *
     * @return string
     */
    public function getProfileRemoveButton()
    {
        return $this->getProfileName('remove');
    }

    /**
     * Returns whether the profile remove button is clicked.
     *
     * @return bool
     */
    public function isProfileRemoveClicked()
    {
        return $this->getProfileValue('remove') === 'remove';
    }

    /**
     * Returns the profile name parameter name.
     *
     * @return string
     */
    public function getProfileNameName()
    {
        return $this->getProfileName('name');
    }

    /**
     * Returns the profile name parameter value.
     *
     * @return string|null
     */
    public function getProfileNameValue()
    {
        return $this->getProfileValue('name');
    }

    /**
     * Returns the profile create button name.
     *
     * @return string
     */
    public function getProfileCreateButton()
    {
        return $this->getProfileName('create');
    }

    /**
     * Returns whether the profile create button is clicked.
     *
     * @return bool
     */
    public function isProfileCreateClicked()
    {
        return $this->getProfileValue('create') === 'create';
    }

    /**
     * Returns the profile parameter full name.
     *
     * @param string $name The profile parameter name
     *
     * @return string
     */
    private function getProfileName($name)
    {
        return $this->tableName . '[' . static::PROFILE . '][' . $name . ']';
    }

    /**
     * Returns the profile parameter value.
     *
     * @param string $name The profile parameter name
     *
     * @return string|null
     */
    private function getProfileValue($name)
    {
        if (isset($this->data[static::PROFILE][$name])) {
            return $this->data[static::PROFILE][$name];
        }

        return null;
    }
}
