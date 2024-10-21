<?php

declare(strict_types=1);

namespace Ekyna\Component\Table\Context\Profile;

/**
 * Interface ProfileInterface
 * @package Ekyna\Component\Table\Context\Profile
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
interface ProfileInterface
{
    /**
     * Returns the key.
     *
     * @return string
     */
    public function getKey(): string;

    /**
     * Returns the name.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Sets the data.
     *
     * @param array $data
     *
     * @return $this|ProfileInterface
     */
    public function setData(array $data): ProfileInterface;

    /**
     * Returns the data (to build the context).
     *
     * @return array
     */
    public function getData(): array;
}
