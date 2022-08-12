<?php
declare(strict_types=1);

namespace Grasch\AdminUi\Block\Adminhtml\Widget\Ui\Components;

use Grasch\AdminUi\Component\EntitiesSelector as UiEntitiesSelector;
use Grasch\AdminUi\Component\EntitiesSelectorFactory as UiEntitiesSelectorFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Layout\Generator\Structure;

class EntitiesSelector extends AbstractComponent
{
    /**
     * @var Structure
     */
    private Structure $structure;

    /**
     * @var UiEntitiesSelectorFactory
     */
    private UiEntitiesSelectorFactory $entitiesSelectorFactory;

    /**
     * @param string $name
     * @param Structure $structure
     * @param UiEntitiesSelectorFactory $entitiesSelectorFactory
     * @param array $dataConfig
     */
    public function __construct(
        string $name,
        Structure $structure,
        UiEntitiesSelectorFactory $entitiesSelectorFactory,
        array $dataConfig = []
    ) {
        parent::__construct($name, $dataConfig);

        $this->structure = $structure;
        $this->entitiesSelectorFactory = $entitiesSelectorFactory;
    }

    /**
     * @return array
     */
    public function buildComponent(): array
    {
        /** @var UiEntitiesSelector $component */
        $component = $this->entitiesSelectorFactory->create([
            'context' => $this->getContext(),
            'data' => [
                'config' => $this->getComponentConfig(),
                'name' => $this->getName()
            ]
        ]);
        $component->prepare();
        $component->setData('layout', ['type' => 'custom_generic']);

        return $this->structure->generate($component)[$this->getName()];
    }

    /**
     * @return array[]
     * @throws LocalizedException
     */
    private function getComponentConfig(): array
    {
        return [
            'dataScope' => $this->getName(),
            'component' => 'uiComponent',
            'label' => 'Label',
            'namespace' => 'product_listing',
            'columnsName' => 'product_columns',
            'selectionsColumnName' => 'ids',
            'grid' => [
                'columns' => [
                    'id' => [
                        'original_name' => 'entity_id',
                        'type' => 'text',
                        'label' => 'ID',
                        'sortOrder' => 10,
                        'fit' => false
                    ]
                ]
            ]
        ];
    }
}
