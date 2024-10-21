<?php

declare(strict_types=1);

namespace Ekyna\Component\Table\Extension\Core\Type;

use Ekyna\Component\Table\AbstractTableType;
use Ekyna\Component\Table\Exception\UnexpectedTypeException;
use Ekyna\Component\Table\Extension\Core\Source\ArraySource;
use Ekyna\Component\Table\Source;
use Ekyna\Component\Table\TableBuilderInterface;
use Ekyna\Component\Table\TableInterface;
use Ekyna\Component\Table\Util\Config;
use Ekyna\Component\Table\View\TableView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class TableType
 * @package Ekyna\Component\Table\Extension\Core\Type
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class TableType extends AbstractTableType
{
    /**
     * @inheritDoc
     */
    public function buildTable(TableBuilderInterface $builder, array $options): void
    {
        $action = $options['url'];
        if (empty($action)) {
            if (isset($_SERVER['REQUEST_URI'])) {
                if (false !== $info = parse_url($url = $_SERVER['REQUEST_URI'])) {
                    $action = isset($info['path']) ? $info['path'] : '/';
                } else {
                    [$action] = explode('?', $url);
                }
            } else {
                $action = '/';
            }
        }

        if (empty($choices = $options['per_page_choices'])) {
            $choices = [15, 30, 45];
        }

        $builder
            ->setPerPageChoices($choices)
            ->setUrl($action)
            ->setSortable($options['sortable'])
            ->setFilterable($options['filterable'])
            ->setBatchable($options['batchable'])
            ->setExportable($options['exportable'])
            ->setConfigurable($options['configurable'])
            ->setProfileable($options['profileable'])
            ->setSelectionMode($options['selection_mode']);

        if (null !== $source = $options['source']) {
            if (is_array($source)) {
                $source = new ArraySource($source);
            }

            if (!$source instanceof Source\SourceInterface) {
                throw new UnexpectedTypeException($source, ['null', 'array',  Source\SourceInterface::class]);
            }

            $builder->setSource($source);

            if ($source instanceof Source\ClassSourceInterface) {
                $builder->setDataClass($source->getClass());
            }
        }

        // TODO Load profile

        // TODO Toggle visible columns
    }

    /**
     * @inheritDoc
     */
    public function buildView(TableView $view, TableInterface $table, array $options): void
    {
        $name = $table->getName();
        $id = $name;
        $fullName = $name;

        $view->vars = array_replace($view->vars, [
            'id'        => $id,
            'name'      => $name,
            'full_name' => $fullName,
            'attr'      => $options['attr'],
            'errors'    => $table->getErrors(),
        ]);
    }

    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'source'           => null,
                'block_name'       => null,
                'url'              => null,
                'sortable'         => true,
                'filterable'       => true,
                'batchable'        => true,
                'exportable'       => false,
                'configurable'     => false,
                'profileable'      => false,
                'selection_mode'   => null,
                'per_page_choices' => [15, 30, 45],
                'attr'             => [],
            ])
            ->setAllowedTypes('source', ['null', 'array', Source\SourceInterface::class])
            ->setAllowedTypes('block_name', ['null', 'string'])
            ->setAllowedTypes('url', ['null', 'string'])
            ->setAllowedTypes('sortable', 'bool')
            ->setAllowedTypes('filterable', 'bool')
            ->setAllowedTypes('batchable', 'bool')
            ->setAllowedTypes('selection_mode', ['null', 'string'])
            ->setAllowedTypes('per_page_choices', 'array')
            ->setAllowedTypes('attr', 'array')
            ->setAllowedValues('selection_mode', function ($mode) {
                if (is_null($mode)) {
                    return true;
                }

                return Config::isValidSelectionMode($mode);
            });
    }

    /**
     * @inheritDoc
     */
    public function getParent(): ?string
    {
        return null;
    }
}
