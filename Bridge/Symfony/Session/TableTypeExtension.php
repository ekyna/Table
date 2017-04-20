<?php

declare(strict_types=1);

namespace Ekyna\Component\Table\Bridge\Symfony\Session;

use Ekyna\Component\Table\Extension\AbstractTableTypeExtension;
use Ekyna\Component\Table\Extension\Core\Type\TableType;
use Ekyna\Component\Table\TableBuilderInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class TableTypeExtension
 * @package Ekyna\Component\Table\Bridge\Symfony\Session
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class TableTypeExtension extends AbstractTableTypeExtension
{
    private RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function buildTable(TableBuilderInterface $builder, array $options): void
    {
        $builder->setSessionStorage(new SessionStorage($this->requestStack->getSession()));
    }

    public static function getExtendedTypes(): array
    {
        return [TableType::class];
    }
}
