<?php

declare(strict_types=1);

namespace Ekyna\Component\Table\Filter;

use Symfony\Component\Form\FormFactoryInterface;

/**
 * Interface FilterConfigBuilderInterface
 * @package Ekyna\Component\Table\Filter
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
interface FilterConfigBuilderInterface extends FilterConfigInterface
{
    /**
     * Sets the type.
     *
     * @param ResolvedFilterTypeInterface $type
     *
     * @return $this|FilterConfigBuilderInterface
     */
    public function setType(ResolvedFilterTypeInterface $type): FilterConfigBuilderInterface;

    /**
     * Sets the filter label.
     *
     * @param string $label
     *
     * @return $this|FilterConfigBuilderInterface
     */
    public function setLabel(string $label): FilterConfigBuilderInterface;

    /**
     * Sets the form factory.
     *
     * @param FormFactoryInterface $formFactory
     *
     * @return $this|FilterConfigBuilderInterface
     */
    public function setFormFactory(FormFactoryInterface $formFactory): FilterConfigBuilderInterface;

    /**
     * Sets the filter position.
     *
     * @param int $position
     *
     * @return $this|FilterConfigBuilderInterface
     */
    public function setPosition(int $position): FilterConfigBuilderInterface;

    /**
     * Sets the filter property path.
     *
     * @param string $path
     *
     * @return $this|FilterConfigBuilderInterface
     */
    public function setPropertyPath(string $path): FilterConfigBuilderInterface;

    /**
     * Builds and returns the filter configuration.
     *
     * @return FilterConfigInterface
     */
    public function getFilterConfig(): FilterConfigInterface;
}
