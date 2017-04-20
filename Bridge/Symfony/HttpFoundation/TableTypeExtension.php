<?php

declare(strict_types=1);

namespace Ekyna\Component\Table\Bridge\Symfony\HttpFoundation;

use Ekyna\Component\Table\Extension\AbstractTableTypeExtension;
use Ekyna\Component\Table\Extension\Core\Type\TableType;
use Ekyna\Component\Table\TableBuilderInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class TableTypeExtension
 * @package Ekyna\Component\Table\Bridge\Symfony\HttpFoundation
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class TableTypeExtension extends AbstractTableTypeExtension
{
    private RequestStack        $requestStack;
    private TranslatorInterface $translator;


    public function __construct(RequestStack $requestStack, TranslatorInterface $translator)
    {
        $this->requestStack = $requestStack;
        $this->translator = $translator;
    }

    public function buildTable(TableBuilderInterface $builder, array $options): void
    {
        $builder->setHttpAdapter(new HttpAdapter($this->requestStack->getSession()->getFlashBag(), $this->translator));
    }

    public static function getExtendedTypes(): array
    {
        return [TableType::class];
    }
}
