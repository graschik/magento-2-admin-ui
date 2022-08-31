<?php
declare(strict_types=1);

namespace Grasch\AdminUi\Model\Widget;

interface MetadataInterface
{
    public const FORM_DATA = 'form_data';
    public const SYNC_FIELD_NAME = 'sync_field_name';

    /**
     * @return array
     */
    public function getFormData(): array;

    /**
     * @param array $data
     * @return mixed
     */
    public function setFormData(array $data): void;

    /**
     * @return string
     */
    public function getSyncFieldName(): string;

    /**
     * @param string $name
     * @return mixed
     */
    public function setSyncFieldName(string $name): void;
}
