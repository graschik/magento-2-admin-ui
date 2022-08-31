<?php
declare(strict_types=1);

namespace Grasch\AdminUi\DataProvider\Widget;

use Grasch\AdminUi\Model\Widget\MetadataInterface;
use Magento\Ui\DataProvider\Modifier\PoolInterface;
use Magento\Ui\DataProvider\ModifierPoolDataProvider;

class DataProvider extends ModifierPoolDataProvider implements DataProviderInterface
{
    /**
     * @var MetadataInterface
     */
    protected MetadataInterface $metadata;

    /**
     * @param $name
     * @param array $meta
     * @param array $data
     * @param PoolInterface|null $pool
     */
    public function __construct(
        $name,
        array $meta = [],
        array $data = [],
        PoolInterface $pool = null
    ) {
        parent::__construct(
            $name,
            '',
            '',
            $meta,
            $data,
            $pool
        );
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->metadata->getFormData();
    }

    /**
     * @param MetadataInterface $metadata
     * @return void
     */
    public function setMetadata(MetadataInterface $metadata): void
    {
        $this->metadata = $metadata;
    }

    /**
     * @return MetadataInterface
     */
    public function getMetadata(): MetadataInterface
    {
        return $this->metadata;
    }
}
