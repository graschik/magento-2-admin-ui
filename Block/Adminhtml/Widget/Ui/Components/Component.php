<?php
declare(strict_types=1);

namespace Grasch\AdminUi\Block\Adminhtml\Widget\Ui\Components;

class Component extends AbstractComponent
{
    /**
     * @return array
     */
    public function buildComponent(): array
    {
        return [
            'name' => $this->getName(),
            'dataScope' => $this->getName(),
            'type' => $this->getContext()->getNamespace() . '.' . $this->getName(),
            'config' => $this->dataConfig
        ];
    }
}
