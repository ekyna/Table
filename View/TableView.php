<?php /** @noinspection PhpPropertyNamingConventionInspection */

declare(strict_types=1);

namespace Ekyna\Component\Table\View;

use Pagerfanta\Pagerfanta;
use Symfony\Component\Form\FormView;

/**
 * Class TableView
 * @package Ekyna\Component\Table\View
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
final class TableView
{
    /**
     * The variables.
     *
     * @var array
     */
    public array $vars = [];

    /**
     * The ui variables.
     *
     * @var array
     */
    public array $ui = [];

    /**
     * The available filters views.
     *
     * @var AvailableFilterView[]
     */
    public array $available_filters = [];

    /**
     * The active filters views.
     *
     * @var ActiveFilterView[]
     */
    public array $active_filters = [];

    /**
     * The current filter label.
     *
     * @var null|string
     */
    public ?string $filter_label = null;

    /**
     * The current filter form view.
     *
     * @var null|FormView
     */
    public ?FormView $filter_form = null;

    /**
     * The columns headers views.
     *
     * @var HeadView[]
     */
    public array $heads = [];

    /**
     * The rows views.
     *
     * @var RowView[]
     */
    public array $rows = [];

    /**
     * The pager instance.
     *
     * @var Pagerfanta
     */
    public Pagerfanta $pager;
}
