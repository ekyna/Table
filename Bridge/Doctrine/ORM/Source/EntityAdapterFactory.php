<?php

namespace Ekyna\Component\Table\Bridge\Doctrine\ORM\Source;

use Doctrine\Common\Persistence\ManagerRegistry;
use Ekyna\Component\Table\Exception\UnexpectedTypeException;
use Ekyna\Component\Table\Source\AdapterFactoryInterface;
use Ekyna\Component\Table\TableInterface;

/**
 * Class EntityAdapterFactory
 * @package Ekyna\Component\Table\Bridge\Doctrine\ORM\Source
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class EntityAdapterFactory implements AdapterFactoryInterface
{
    /**
     * @var ManagerRegistry
     */
    private $registry;


    /**
     * Constructor.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * @inheritDoc
     */
    public function createAdapter(TableInterface $table)
    {
        $source = $table->getConfig()->getSource();

        if (!$source instanceof EntitySource) {
            throw new UnexpectedTypeException($source, EntitySource::class);
        }

        /** @var \Doctrine\ORM\EntityManagerInterface $manager */
        $manager = $this->registry->getManagerForClass($source->getClass());

        return new EntityAdapter($table, $manager);
    }
}
