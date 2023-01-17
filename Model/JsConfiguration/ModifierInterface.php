<?php
declare(strict_types=1);

namespace Grasch\AdminUi\Model\JsConfiguration;

use Magento\Framework\View\Element\UiComponent\ContextInterface;

interface ModifierInterface
{
    /**
     * Run modifiers
     *
     * @param array $configuration
     * @param ContextInterface $context
     * @param array $params
     * @return array
     */
    public function execute(
        array $configuration,
        ContextInterface $context,
        array $params = []
    ): array;
}
