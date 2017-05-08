<?php

namespace Ekyna\Component\Table\Extension;

use Ekyna\Component\Table\TableBuilderInterface;
use Ekyna\Component\Table\TableInterface;
use Ekyna\Component\Table\View\TableView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AbstractTableTypeExtension
 * @package Ekyna\Component\Table\Extension
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
abstract class AbstractTableTypeExtension implements TableTypeExtensionInterface
{
    /**
     * @inheritDoc
     */
    public function buildTable(TableBuilderInterface $builder, array $options)
    {
    }

    /**
     * @inheritDoc
     */
    public function buildView(TableView $view, TableInterface $table, array $options)
    {
    }

    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
    }
}
