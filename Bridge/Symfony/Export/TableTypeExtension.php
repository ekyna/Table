<?php

namespace Ekyna\Component\Table\Bridge\Symfony\Export;

use Ekyna\Component\Table\Extension\AbstractTableTypeExtension;
use Ekyna\Component\Table\Extension\Core\Type\TableType;
use Ekyna\Component\Table\TableBuilderInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class TableTypeExtension
 * @package Ekyna\Component\Table\Bridge\Symfony\Export
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class TableTypeExtension extends AbstractTableTypeExtension
{
    /**
     * @var SerializerInterface
     */
    private $serializer;


    /**
     * Constructor.
     *
     * @param SerializerInterface $serializer
     */
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @inheritDoc
     */
    public function buildTable(TableBuilderInterface $builder, array $options)
    {
        $builder->addExportAdapter(new SerializerAdapter($this->serializer));
    }

    /**
     * @inheritDoc
     */
    public function getExtendedType()
    {
        return TableType::class;
    }
}
