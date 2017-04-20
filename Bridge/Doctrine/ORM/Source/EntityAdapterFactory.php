<?php

declare(strict_types=1);

namespace Ekyna\Component\Table\Bridge\Doctrine\ORM\Source;

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Ekyna\Component\Table\Exception\UnexpectedTypeException;
use Ekyna\Component\Table\Source\AdapterFactoryInterface;
use Ekyna\Component\Table\Source\AdapterInterface;
use Ekyna\Component\Table\TableInterface;

/**
 * Class EntityAdapterFactory
 * @package Ekyna\Component\Table\Bridge\Doctrine\ORM\Source
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class EntityAdapterFactory implements AdapterFactoryInterface
{
    private ManagerRegistry $registry;


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
    public function createAdapter(TableInterface $table): AdapterInterface
    {
        $source = $table->getConfig()->getSource();

        if (!$source instanceof EntitySource) {
            throw new UnexpectedTypeException($source, EntitySource::class);
        }

        /** @var EntityManagerInterface $manager */
        $manager = $this->registry->getManagerForClass($source->getClass());

        return new EntityAdapter($table, $manager);
    }
}
