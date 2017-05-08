<?php

namespace Ekyna\Component\Table\Bridge\Symfony\Session;

use Ekyna\Component\Table\Extension\AbstractTableTypeExtension;
use Ekyna\Component\Table\Extension\Core\Type\TableType;
use Ekyna\Component\Table\TableBuilderInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Class TableTypeExtension
 * @package Ekyna\Component\Table\Bridge\Symfony\Session
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class TableTypeExtension extends AbstractTableTypeExtension
{
    /**
     * @var SessionInterface
     */
    private $session;


    /**
     * Constructor.
     *
     * @param SessionInterface $session
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @inheritDoc
     */
    public function buildTable(TableBuilderInterface $builder, array $options)
    {
        $builder->setSessionStorage(new SessionStorage($this->session));
    }

    /**
     * @inheritDoc
     */
    public function getExtendedType()
    {
        return TableType::class;
    }
}
