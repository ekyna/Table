<?php

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
     * @return $this
     */
    public function setType(ResolvedFilterTypeInterface $type);

    /**
     * Sets the filter label.
     *
     * @param string $label
     *
     * @return $this
     */
    public function setLabel($label);

    /**
     * Sets the form factory.
     *
     * @param FormFactoryInterface $formFactory
     *
     * @return $this
     */
    public function setFormFactory(FormFactoryInterface $formFactory);

    /**
     * Sets the filter position.
     *
     * @param int $position
     *
     * @return $this
     */
    public function setPosition($position);

    /**
     * Sets the filter property path.
     *
     * @param string $path
     *
     * @return $this
     */
    public function setPropertyPath($path);

    /**
     * Builds and returns the filter configuration.
     *
     * @return FilterConfigInterface
     */
    public function getFilterConfig();
}
