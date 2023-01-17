<?php
declare(strict_types=1);

namespace Grasch\AdminUi\Model\JsConfiguration\Modifiers;

use Grasch\AdminUi\Model\JsConfiguration\ModifierInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;

class MultiselectComponentModifier extends AbstractModifier implements ModifierInterface
{
    /**
     * Modify multiselect component
     *
     * @param array $configuration
     * @param ContextInterface $context
     * @param array $params
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function execute(
        array $configuration,
        ContextInterface $context,
        array $params = []
    ): array {
        if (empty($params['limiter'])) {
            return $configuration;
        }

        $path = sprintf(
            'components/%s/config',
            implode('/children/', explode('.', $params['limiter']['selectionsProvider']))
        );

        return $this->_arrayManager->merge(
            $path,
            $configuration,
            [
                'limit' => $params['limiter']['limit'],
                'component' => 'Grasch_AdminUi/js/view/grid/columns/multiselect-with-limit'
            ]
        );
    }
}
