<?php

namespace Ekyna\Component\Table\Bridge\Symfony\Export;

use Ekyna\Component\Table\Export\AbstractAdapter;
use Ekyna\Component\Table\TableInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class SerializerAdapter
 * @package Ekyna\Component\Table\Bridge\Symfony\Export
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class SerializerAdapter extends AbstractAdapter
{
    /**
     * @var SerializerInterface
     */
    private $serializer;


    /**
     * Constructor.
     *
     * @param SerializerInterface $serializer
     */
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @inheritDoc
     */
    public function export(TableInterface $table, $format)
    {
        return $this->serializer->serialize($this->getSelectedData($table), $format);
    }

    /**
     * @inheritDoc
     */
    public function getMimeType($format)
    {
        return 'application/' . $format;
    }

    /**
     * @inheritDoc
     */
    public function supports($format)
    {
        return in_array($format, ['json', 'xml'], true);
    }
}
