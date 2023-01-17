<?php
declare(strict_types=1);

namespace Grasch\AdminUi\Model\Widget;

use Magento\Framework\DataObject;

class Metadata extends DataObject implements MetadataInterface
{
    /**
     * @inheritDoc
     */
    public function getFormData(): array
    {
        return $this->getData(self::FORM_DATA);
    }

    /**
     * @inheritDoc
     */
    public function setFormData(array $data): void
    {
        $this->setData(self::FORM_DATA, $data);
    }

    /**
     * @inheritDoc
     */
    public function getSyncFieldName(): string
    {
        return $this->getData(self::SYNC_FIELD_NAME);
    }

    /**
     * @inheritDoc
     */
    public function setSyncFieldName(string $name): void
    {
        $this->setData(self::SYNC_FIELD_NAME, $name);
    }
}
