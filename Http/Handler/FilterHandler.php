<?php

namespace Ekyna\Component\Table\Http\Handler;

use Ekyna\Component\Table\Context\ActiveFilter;
use Ekyna\Component\Table\Exception\LogicException;
use Ekyna\Component\Table\TableInterface;

/**
 * Class FilterHandler
 * @package Ekyna\Component\Table\Http\Handler
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class FilterHandler implements HandlerInterface
{
    /**
     * @inheritDoc
     */
    public function execute(TableInterface $table, $request)
    {
        // Abort if table's filters are not enabled
        if (!$table->getConfig()->isFilterable()) {
            return null;
        }

        $context = $table->getContext();
        $parameters = $table->getParametersHelper();

        // Remove filter request
        if (!empty($id = $parameters->getRemoveFilterValue())) {
            if ($context->hasActiveFilter($id)) {
                $context->removeActiveFilter($id);

                return $table->getConfig()->getHttpAdapter()->redirect($table);
            }
        }

        // Add filter request
        if (empty($name = $parameters->getAddFilterValue())) {
            return null;
        }

        if (!$table->hasFilter($name)) {
            return null;
        }

        $filter = $table->getFilter($name);
        $activeFilter = new ActiveFilter($name . '_' . count($context->getActiveFilters()), $name);

        $form = $filter->createForm();
        if (!isset($form['operator'], $form['value'])) {
            throw new LogicException("Form must have both 'operator' and 'value' children.");
        }
        $form->setData($activeFilter);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $context->addActiveFilter($activeFilter);

            return $table->getConfig()->getHttpAdapter()->redirect($table);
        }

        $context->setFilterLabel($filter->getLabel());
        $context->setFilterForm($form);

        return null;
    }
}
