<?php

declare(strict_types=1);

namespace Ekyna\Component\Table\Extension\Core\Source;

use Ekyna\Component\Table\Source\SourceInterface;

/**
 * Class ArraySource
 * @package Ekyna\Component\Table\Extension\Core\Source
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class ArraySource implements SourceInterface
{
    private array $data;


    /**
     * Constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->setData($data);
    }

    /**
     * Sets the data.
     *
     * @param array $data
     */
    private function setData(array $data)
    {
        $this->data = [];

        foreach ($data as $key => $value) {
            $this->data[$key] = (object)$value;
        }
    }

    /**
     * Returns the source data.
     *
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @inheritDoc
     */
    public static function getFactory(): string
    {
        return ArrayAdapterFactory::class;
    }
}
