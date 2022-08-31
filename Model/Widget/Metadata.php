<?php
declare(strict_types=1);

namespace Grasch\AdminUi\Model\Widget;

use Magento\Framework\DataObject;

class Metadata extends DataObject implements MetadataInterface
{
    /**
     * @return array
     */
    public function getFormData(): array
    {
        return $this->getData(self::FORM_DATA);
    }

    /**
     * @param array $data
     * @return void
     */
    public function setFormData(array $data): void
    {
        $this->setData(self::FORM_DATA, $data);
    }

    /**
     * @return string
     */
    public function getSyncFieldName(): string
    {
        return $this->getData(self::SYNC_FIELD_NAME);
    }

    /**
     * @param string $name
     * @return void
     */
    public function setSyncFieldName(string $name): void
    {
        $this->setData(self::SYNC_FIELD_NAME, $name);
    }
}
