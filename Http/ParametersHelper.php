<?php

declare(strict_types=1);

namespace Ekyna\Component\Table\Http;

use Ekyna\Component\Table\Column\ColumnInterface;
use Ekyna\Component\Table\Context\ActiveFilter;
use Ekyna\Component\Table\Filter\FilterInterface;
use Ekyna\Component\Table\Util\ColumnSort;
use Ekyna\Component\Table\Util\Config;

use function array_replace;
use function strtolower;

/**
 * Class ParametersHelper
 * @package Ekyna\Component\Table\Request
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
final class ParametersHelper
{
    public const SORT            = 'sort';
    public const PAGE_NUM        = 'page';
    public const ADD_FILTER      = 'add_filter';
    public const REMOVE_FILTER   = 'remove_filter';
    public const IDENTIFIERS     = 'identifiers';
    public const ALL             = 'all';
    public const BATCH           = 'batch';
    public const ACTION          = 'action';
    public const EXPORT          = 'export';
    public const FORMAT          = 'format';
    public const CONFIG          = 'config';
    public const VISIBLE_COLUMNS = 'columns';
    public const MAX_PER_PAGE    = 'page_max';
    public const PROFILE         = 'profile';


    private string $tableName;
    private ?string $selectionMode;

    private bool $default;
    private array $data;


    /**
     * Constructor.
     *
     * @param string $tableName
     * @param string|null $selectionMode
     */
    public function __construct(string $tableName, string $selectionMode = null)
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
            self::VISIBLE_COLUMNS => [],
            self::MAX_PER_PAGE    => null,
            self::CONFIG          => null,
            self::PAGE_NUM        => null,
            self::SORT            => null,
            self::ADD_FILTER      => null,
            self::REMOVE_FILTER   => null,
            self::IDENTIFIERS     => [],
            self::ALL             => null,
            self::BATCH           => null,
            self::ACTION          => null,
            self::EXPORT          => null,
            self::FORMAT          => null,
            self::PROFILE         => [],
        ], $data);
    }

    /**
     * Returns whether the parameters values are the default ones.
     *
     * @return bool
     */
    public function isDefault(): bool
    {
        return $this->default;
    }

    /**
     * Returns the columns parameter name.
     *
     * @return string
     */
    public function getColumnsName(): string
    {
        return $this->tableName . '[' . self::VISIBLE_COLUMNS . '][]';
    }

    /**
     * Returns the columns parameter value.
     *
     * @return array
     */
    public function getColumnsValue(): array
    {
        return $this->data[self::VISIBLE_COLUMNS] ?: [];
    }

    /**
     * Returns the "max per page" parameter name.
     *
     * @return string
     */
    public function getMaxPerPageName(): string
    {
        return $this->tableName . '[' . self::MAX_PER_PAGE . ']';
    }

    /**
     * Returns the "max per page" parameter value.
     *
     * @return string|null
     */
    public function getMaxPerPageValue(): ?string
    {
        return $this->data[self::MAX_PER_PAGE];
    }

    /**
     * Returns the config button name.
     *
     * @return string
     */
    public function getConfigButton(): string
    {
        return $this->tableName . '[' . self::CONFIG . ']';
    }

    /**
     * Returns whether the config button is clicked.
     *
     * @return bool
     */
    public function isConfigClicked(): bool
    {
        return $this->data[self::CONFIG] === self::CONFIG;
    }

    /**
     * Returns the page parameter name.
     *
     * @return string
     */
    public function getPageName(): string
    {
        return $this->tableName . '[' . self::PAGE_NUM . ']';
    }

    /**
     * Returns the page parameter value.
     *
     * @return int
     */
    public function getPageValue(): int
    {
        return (int)$this->data[self::PAGE_NUM];
    }

    /**
     * Returns the sort parameter name.
     *
     * @return string
     */
    public function getSortName(): string
    {
        return $this->tableName . '[' . self::SORT . ']';
    }

    /**
     * Returns the sort parameter name.
     *
     * @return array|null
     */
    public function getSortValue(): ?array
    {
        return $this->data[self::SORT];
    }

    /**
     * Returns the sort column href.
     *
     * @param ColumnInterface $column
     *
     * @return string
     */
    public function getSortHref(ColumnInterface $column): string
    {
        $parameter = $this->getSortName();
        $dir = ($column->getSortDirection() === ColumnSort::ASC ? ColumnSort::DESC : ColumnSort::ASC);

        return '?' . $parameter . '[by]=' . $column->getName() . '&' . $parameter . '[dir]=' . $dir;
    }

    /**
     * Returns the add filter parameter name.
     *
     * @return string
     */
    public function getAddFilterName(): string
    {
        return $this->tableName . '[' . self::ADD_FILTER . ']';
    }

    /**
     * Returns the add filter parameter value.
     *
     * @return string|null
     */
    public function getAddFilterValue(): ?string
    {
        return $this->data[self::ADD_FILTER];
    }

    /**
     * Returns the add filter href.
     *
     * @param FilterInterface $filter
     *
     * @return string
     */
    public function getAddFilterHref(FilterInterface $filter): string
    {
        return '?' . $this->getAddFilterName() . '=' . $filter->getName();
    }

    /**
     * Returns the remove filter parameter name.
     *
     * @return string
     */
    public function getRemoveFilterName(): string
    {
        return $this->tableName . '[' . self::REMOVE_FILTER . ']';
    }

    /**
     * Returns the remove filter parameter value.
     *
     * @return string|null
     */
    public function getRemoveFilterValue(): ?string
    {
        return $this->data[self::REMOVE_FILTER];
    }

    /**
     * Returns the remove filter href.
     *
     * @param ActiveFilter $filter
     *
     * @return string
     */
    public function getRemoveFilterHref(ActiveFilter $filter): string
    {
        return '?' . $this->getRemoveFilterName() . '=' . $filter->getId();
    }

    /**
     * Returns the identifiers parameter name.
     *
     * @return string
     */
    public function getIdentifiersName(): string
    {
        $name = $this->tableName . '[' . self::IDENTIFIERS . ']';

        if ($this->selectionMode === Config::SELECTION_MULTIPLE) {
            $name .= '[]';
        }

        return $name;
    }

    /**
     * Returns the identifiers parameter value.
     *
     * @return array
     */
    public function getIdentifiersValue(): array
    {
        return $this->data[self::IDENTIFIERS] ?: [];
    }

    /**
     * Returns the "all elements" parameter name.
     *
     * @return string
     */
    public function getAllName(): string
    {
        return $this->tableName . '[' . self::ALL . ']';
    }

    /**
     * Returns the "all elements" parameter value.
     *
     * @return bool
     */
    public function getAllValue(): bool
    {
        return $this->data[self::ALL] ? (bool)$this->data[self::ALL] : false;
    }

    /**
     * Returns the batch button name.
     *
     * @return string
     */
    public function getBatchButton(): string
    {
        return $this->tableName . '[' . self::BATCH . ']';
    }

    /**
     * Returns whether the batch button is clicked.
     *
     * @return bool
     */
    public function isBatchClicked(): bool
    {
        return $this->data[self::BATCH] === self::BATCH;
    }

    /**
     * Returns the action parameter name.
     *
     * @return string
     */
    public function getActionName(): string
    {
        return $this->tableName . '[' . self::ACTION . ']';
    }

    /**
     * Returns the action parameter value.
     *
     * @return string|null
     */
    public function getActionValue(): ?string
    {
        return $this->data[self::ACTION];
    }

    /**
     * Returns the export button name.
     *
     * @return string
     */
    public function getExportButton(): string
    {
        return $this->tableName . '[' . self::EXPORT . ']';
    }

    /**
     * Returns whether the export button is clicked.
     *
     * @return bool
     */
    public function isExportClicked(): bool
    {
        return $this->data[self::EXPORT] === self::EXPORT;
    }

    /**
     * Returns the format parameter name.
     *
     * @return string
     */
    public function getFormatName(): string
    {
        return $this->tableName . '[' . self::FORMAT . ']';
    }

    /**
     * Returns the format parameter value.
     *
     * @return string|null
     */
    public function getFormatValue(): ?string
    {
        return $this->data[self::FORMAT] ? strtolower($this->data[self::FORMAT]) : null;
    }

    /**
     * Returns the profile choice parameter name.
     *
     * @return string
     */
    public function getProfileChoiceName(): string
    {
        return $this->getProfileName('choice');
    }

    /**
     * Returns the profile choice parameter value.
     *
     * @return string|null
     */
    public function getProfileChoiceValue(): ?string
    {
        return $this->getProfileValue('choice');
    }

    /**
     * Returns the profile load button name.
     *
     * @return string
     */
    public function getProfileLoadButton(): string
    {
        return $this->getProfileName('load');
    }

    /**
     * Returns whether the profile load button is clicked.
     *
     * @return bool
     */
    public function isProfileLoadClicked(): bool
    {
        return $this->getProfileValue('load') === 'load';
    }

    /**
     * Returns the profile edit button name.
     *
     * @return string
     */
    public function getProfileEditButton(): string
    {
        return $this->getProfileName('edit');
    }

    /**
     * Returns whether the profile edit button is clicked.
     *
     * @return bool
     */
    public function isProfileEditClicked(): bool
    {
        return $this->getProfileValue('edit') === 'edit';
    }

    /**
     * Returns the profile remove button name.
     *
     * @return string
     */
    public function getProfileRemoveButton(): string
    {
        return $this->getProfileName('remove');
    }

    /**
     * Returns whether the profile remove button is clicked.
     *
     * @return bool
     */
    public function isProfileRemoveClicked(): bool
    {
        return $this->getProfileValue('remove') === 'remove';
    }

    /**
     * Returns the profile name parameter name.
     *
     * @return string
     */
    public function getProfileNameName(): string
    {
        return $this->getProfileName('name');
    }

    /**
     * Returns the profile name parameter value.
     *
     * @return string|null
     */
    public function getProfileNameValue(): ?string
    {
        return $this->getProfileValue('name');
    }

    /**
     * Returns the profile create button name.
     *
     * @return string
     */
    public function getProfileCreateButton(): string
    {
        return $this->getProfileName('create');
    }

    /**
     * Returns whether the profile create button is clicked.
     *
     * @return bool
     */
    public function isProfileCreateClicked(): bool
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
    private function getProfileName(string $name): string
    {
        return $this->tableName . '[' . self::PROFILE . '][' . $name . ']';
    }

    /**
     * Returns the profile parameter value.
     *
     * @param string $name The profile parameter name
     *
     * @return string|null
     */
    private function getProfileValue(string $name): ?string
    {
        if (isset($this->data[self::PROFILE][$name])) {
            return $this->data[self::PROFILE][$name];
        }

        return null;
    }
}
