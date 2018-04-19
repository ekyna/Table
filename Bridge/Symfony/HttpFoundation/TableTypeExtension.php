<?php

namespace Ekyna\Component\Table\Bridge\Symfony\HttpFoundation;

use Ekyna\Component\Table\Extension\AbstractTableTypeExtension;
use Ekyna\Component\Table\Extension\Core\Type\TableType;
use Ekyna\Component\Table\TableBuilderInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class TableTypeExtension
 * @package Ekyna\Component\Table\Bridge\Symfony\HttpFoundation
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class TableTypeExtension extends AbstractTableTypeExtension
{
    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    /**
     * @var TranslatorInterface
     */
    private $translator;


    /**
     * Constructor.
     *
     * @param FlashBagInterface   $flashBag
     * @param TranslatorInterface $translator
     */
    public function __construct(FlashBagInterface $flashBag, TranslatorInterface $translator)
    {
        $this->flashBag = $flashBag;
        $this->translator = $translator;
    }

    /**
     * @inheritDoc
     */
    public function buildTable(TableBuilderInterface $builder, array $options)
    {
        $builder->setHttpAdapter(new HttpAdapter($this->flashBag, $this->translator));
    }

    /**
     * @inheritDoc
     */
    public function getExtendedType()
    {
        return TableType::class;
    }
}
