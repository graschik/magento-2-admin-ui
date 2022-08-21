<?php
declare(strict_types=1);

namespace Grasch\AdminUi\Model\JsConfiguration\Modifiers;

use Grasch\AdminUi\Model\JsConfiguration\ModifierInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;

class ListingComponentModifier extends AbstractModifier implements ModifierInterface
{
    /**
     * @param array $configuration
     * @param ContextInterface $context
     * @param array $params
     * @return array
     */
    public function execute(
        array $configuration,
        ContextInterface $context,
        array $params = []
    ): array {
        if (empty($params['columns_name'])) {
            return $configuration;
        }

        $path = sprintf(
            '%s/children/%s/config',
            $this->getPathToMainComponent($context),
            $params['columns_name']
        );

        return $this->_arrayManager->merge(
            $path,
            $configuration,
            $this->getConfig()
        );
    }

    /**
     * @return \false[][]
     */
    private function getConfig(): array
    {
        return [
            'editorConfig' => [
                'enabled' => false,
            ],
            'dndConfig' => [
                'enabled' => false
            ],
            'resizeConfig' => [
                'enabled' => false
            ],
        ];
    }
}
