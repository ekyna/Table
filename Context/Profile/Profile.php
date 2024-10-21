<?php

declare(strict_types=1);

namespace Ekyna\Component\Table\Context\Profile;

use Ekyna\Component\Table\Context\Context;
use Ekyna\Component\Table\Exception\RuntimeException;

/**
 * Class Profile
 * @package Ekyna\Component\Table\Context\Profile
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class Profile implements ProfileInterface
{
    public static function create(string $key, string $name, Context $context): Profile
    {
        return new Profile(
            $key, $name, $context->toArray()
        );
    }

    private function __construct(
        private readonly string $key,
        private readonly string $name,
        private readonly array  $data,
    ) {
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setData(array $data): ProfileInterface
    {
        throw new RuntimeException('Can\'t edit hard profile');
    }
}
