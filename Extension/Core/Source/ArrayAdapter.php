<?php

declare(strict_types=1);

namespace Ekyna\Component\Table\Extension\Core\Source;

use Closure;
use Ekyna\Component\Table\Context\ContextInterface;
use Ekyna\Component\Table\Exception;
use Ekyna\Component\Table\Source;
use Ekyna\Component\Table\Util;
use Pagerfanta\Adapter\ArrayAdapter as PagerAdapter;

use function array_filter;
use function in_array;
use function is_null;
use function strlen;
use function strpos;
use function strtolower;
use function uasort;

/**
 * Class ArrayAdapter
 * @package Ekyna\Component\Table\Extension\Core\Source
 * @author  Etienne Dauvergne <contact@ekyna.com>
 *
 * @method ArraySource getSource()
 */
class ArrayAdapter extends Source\AbstractAdapter
{
    /** @var callable[] */
    private array $sortClosures;
    /** @var callable[] */
    private array    $filterClosures;
    private ?Closure $identifiersClosures = null;


    /**
     * Adds the filter closure.
     *
     * @param Closure $closure
     */
    public function addFilterClosure(Closure $closure)
    {
        $this->filterClosures[] = $closure;
    }

    /**
     * Adds the sort closure.
     *
     * @param Closure $closure
     */
    public function addSortClosure(Closure $closure)
    {
        $this->sortClosures[] = $closure;
    }

    /**
     * Builds a filter closure from the active filter.
     *
     * @param string $propertyPath
     * @param int    $operator
     * @param mixed  $value
     *
     * @return Closure
     */
    public function buildFilterClosure(string $propertyPath, int $operator, $value): Closure
    {
        $rowValue = function ($data) use ($propertyPath) {
            return $this->propertyAccessor->getValue($data, $propertyPath);
        };

        switch ($operator) {
            case Util\FilterOperator::EQUAL:
                return function ($row) use ($value, $rowValue) {
                    return $value == $rowValue($row);
                };
            case Util\FilterOperator::NOT_EQUAL:
                return function ($row) use ($value, $rowValue) {
                    return $value != $rowValue($row);
                };
            case Util\FilterOperator::LOWER_THAN:
                return function ($row) use ($value, $rowValue) {
                    return $value < $rowValue($row);
                };
            case Util\FilterOperator::LOWER_THAN_OR_EQUAL:
                return function ($row) use ($value, $rowValue) {
                    return $value <= $rowValue($row);
                };
            case Util\FilterOperator::GREATER_THAN:
                return function ($row) use ($value, $rowValue) {
                    return $value > $rowValue($row);
                };
            case Util\FilterOperator::GREATER_THAN_OR_EQUAL:
                return function ($row) use ($value, $rowValue) {
                    return $value >= $rowValue($row);
                };
            case Util\FilterOperator::IN:
            case Util\FilterOperator::MEMBER:
                return function ($row) use ($value, $rowValue) {
                    return in_array($value, (array)$rowValue($row));
                };
            case Util\FilterOperator::NOT_IN:
            case Util\FilterOperator::NOT_MEMBER:
                return function ($row) use ($value, $rowValue) {
                    return !in_array($value, (array)$rowValue($row));
                };
            case Util\FilterOperator::LIKE:
                $value = strtolower($value);

                return function ($row) use ($value, $rowValue) {
                    return false !== strpos(strtolower((string)$rowValue($row)), $value);
                };
            case Util\FilterOperator::NOT_LIKE:
                $value = strtolower($value);

                return function ($row) use ($value, $rowValue) {
                    return false === strpos(strtolower((string)$rowValue($row)), $value);
                };
            case Util\FilterOperator::START_WITH:
                $value = strtolower($value);
                $length = strlen($value);

                return function ($row) use ($value, $length, $rowValue) {
                    return (substr(strtolower((string)$rowValue($row)), 0, $length) === $value);
                };
            case Util\FilterOperator::NOT_START_WITH:
                $value = strtolower($value);
                $length = strlen($value);

                return function ($row) use ($value, $length, $rowValue) {
                    return !(substr(strtolower((string)$rowValue($row)), 0, $length) === $value);
                };
            case Util\FilterOperator::END_WITH:
                $value = strtolower($value);
                $length = strlen($value);

                return function ($row) use ($value, $length, $rowValue) {
                    return (substr(strtolower((string)$rowValue($row)), -$length) === $value);
                };
            case Util\FilterOperator::NOT_END_WITH:
                $value = strtolower($value);
                $length = strlen($value);

                return function ($row) use ($value, $length, $rowValue) {
                    return !(substr(strtolower((string)$rowValue($row)), -$length) === $value);
                };
            case Util\FilterOperator::IS_NULL:
                return function ($row) use ($rowValue) {
                    return is_null($rowValue($row));
                };
            case Util\FilterOperator::IS_NOT_NULL:
                return function ($row) use ($rowValue) {
                    return !is_null($rowValue($row));
                };
        }

        throw new Exception\InvalidArgumentException('Unexpected filter operator.');
    }

    /**
     * Builds the sort closure.
     *
     * @param string $propertyPath
     * @param string $direction
     *
     * @return Closure
     */
    public function buildSortClosure(string $propertyPath, string $direction): Closure
    {
        $value = function ($data) use ($propertyPath) {
            return $this->propertyAccessor->getValue($data, $propertyPath);
        };

        if ($direction === Util\ColumnSort::ASC) {
            return function ($rowA, $rowB) use ($value): int {
                $a = $value($rowA);
                $b = $value($rowB);

                if ($a === $b) {
                    return 0;
                }

                return $a < $b ? -1 : 1;
            };
        } elseif ($direction === Util\ColumnSort::DESC) {
            return function ($rowA, $rowB) use ($value): int {
                $a = $value($rowA);
                $b = $value($rowB);

                if ($a === $b) {
                    return 0;
                }

                return $a > $b ? -1 : 1;
            };
        }

        throw new Exception\InvalidArgumentException('Unexpected column sort direction.');
    }

    /**
     * @inheritDoc
     */
    protected function validateSource(Source\SourceInterface $source): void
    {
        if ($source instanceof ArraySource) {
            return;
        }

        throw new Exception\UnexpectedTypeException($source, ArraySource::class);
    }

    /**
     * @inheritDoc
     */
    protected function initializeSorting(ContextInterface $context): void
    {
        if (!$context->getActiveSort() && !empty($sorts = $this->table->getConfig()->getDefaultSorts())) {
            foreach ($sorts as $propertyPath => $direction) {
                $closure = $this->buildSortClosure($propertyPath, $direction);

                $this->addSortClosure($closure);
            }

            return;
        }

        parent::initializeSorting($context);
    }

    /**
     * @inheritDoc
     */
    protected function initializeSelection(ContextInterface $context): void
    {
        $identifiers = $context->getSelectedIdentifiers();

        $this->identifiersClosures = function ($key) use ($identifiers) {
            return in_array($key, $identifiers);
        };
    }

    /**
     * @inheritDoc
     */
    protected function getSelectedRows(): array
    {
        $results = $this->applyClosures($this->getSource()->getData());

        $rows = [];
        foreach ($results as $id => $result) {
            $rows[] = $this->createRow((string)$id, $result);
        }

        return $rows;
    }

    /**
     * @inheritDoc
     */
    protected function getPagerAdapter(): PagerAdapter
    {
        // Filter and sort the data
        $data = $this->getSource()->getData();
        $data = $this->applyClosures($data);

        return new PagerAdapter($data);
    }

    /**
     * @inheritDoc
     */
    protected function reset(): void
    {
        parent::reset();

        $this->sortClosures = [];
        $this->filterClosures = [];
        $this->identifiersClosures = null;
    }

    /**
     * Applies the filters and sort closures.
     *
     * @param array $data
     *
     * @return array
     */
    private function applyClosures(array $data): array
    {
        if (!empty($this->filterClosures)) {
            $data = array_filter($data, function ($datum) {
                foreach ($this->filterClosures as $closure) {
                    if (!$closure($datum)) {
                        return false;
                    }
                }

                return true;
            });
        }

        if (!empty($this->sortClosures)) {
            uasort($data, function ($rowA, $rowB) {
                foreach ($this->sortClosures as $closure) {
                    if (0 !== $result = $closure($rowA, $rowB)) {
                        return $result;
                    }
                }

                return 0;
            });
        }

        if ($this->identifiersClosures) {
            $data = array_filter($data, $this->identifiersClosures, ARRAY_FILTER_USE_KEY);
        }

        return $data;
    }
}
