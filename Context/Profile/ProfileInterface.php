<?php

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
    public function getKey();

    /**
     * Sets the table hash.
     *
     * @param string $hash
     *
     * @return $this
     */
    public function setTableHash($hash);

    /**
     * Returns the table hash.
     *
     * @return string
     */
    public function getTableHash();

    /**
     * Sets the name.
     *
     * @param string $name
     *
     * @return $this
     */
    public function setName($name);

    /**
     * Returns the name.
     *
     * @return string
     */
    public function getName();

    /**
     * Sets the data.
     *
     * @param array $data
     *
     * @return $this
     */
    public function setData(array $data);

    /**
     * Returns the data (to build the context).
     *
     * @return array
     */
    public function getData();
}
