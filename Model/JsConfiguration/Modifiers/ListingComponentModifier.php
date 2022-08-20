<?php
declare(strict_types=1);

namespace Grasch\AdminUi\Model\JsConfiguration\Modifiers;

use Grasch\AdminUi\Model\JsConfiguration\ModifierInterface;
use Magento\Framework\Stdlib\ArrayManager;
use Magento\Framework\View\Element\UiComponent\ContextInterface;

class ListingComponentModifier implements ModifierInterface
{
    /**
     * @var ArrayManager
     */
    private ArrayManager $arrayManager;

    /**
     * @param ArrayManager $arrayManager
     */
    public function __construct(
        ArrayManager $arrayManager
    ) {
        $this->arrayManager = $arrayManager;
    }

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
            'components/%s/children/%s/children/%s/config',
            $context->getNamespace(),
            $context->getNamespace(),
            $params['columns_name']
        );
        $configuration = $this->arrayManager->merge(
            $path,
            $configuration,
            $this->getConfig()
        );

        return $configuration;
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
