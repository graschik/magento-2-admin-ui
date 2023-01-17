<?php
declare(strict_types=1);

namespace Grasch\AdminUi\DataProvider\Widget;

use Grasch\AdminUi\Model\Widget\MetadataInterface;
use Magento\Framework\View\Element\UiComponent\DataProvider\DataProviderInterface as BaseDataProviderInterface;

interface DataProviderInterface extends BaseDataProviderInterface
{
    /**
     * Set metadata
     *
     * @param MetadataInterface $metadata
     * @return mixed
     */
    public function setMetadata(MetadataInterface $metadata): void;

    /**
     * Get metadata
     *
     * @return MetadataInterface
     */
    public function getMetadata(): MetadataInterface;
}
