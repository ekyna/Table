<?php

namespace Ekyna\Component\Table\Context\Profile;

use Ekyna\Component\Table\TableInterface;

/**
 * Interface StorageInterface
 * @package Ekyna\Component\Table\Context\Profile
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
interface StorageInterface
{
    /**
     * Returns whether a profile exists for the given key.
     *
     * @param string $key
     *
     * @return bool
     */
    public function has($key);

    /**
     * Returns the profile for the given key.
     *
     * @param string $key
     *
     * @return ProfileInterface
     */
    public function get($key);

    /**
     * Returns all the profiles for the given table.
     *
     * @param TableInterface $table
     *
     * @return ProfileInterface[]
     */
    public function all(TableInterface $table);

    /**
     * Creates the given profile.
     *
     * @param TableInterface $table
     * @param string         $name
     */
    public function create(TableInterface $table, $name);

    /**
     * Saves the given profile.
     *
     * @param ProfileInterface $profile
     */
    public function save(ProfileInterface $profile);

    /**
     * Removes the given profile.
     *
     * @param ProfileInterface $profile
     */
    public function remove(ProfileInterface $profile);
}
