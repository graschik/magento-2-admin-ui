<?php

namespace Grasch\AdminUi\ViewModel;

use Magento\Framework\Module\Manager;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\PageBuilder\Model\Config;

class PageBuilder implements ArgumentInterface
{
    /**
     * @var Config
     */
    private Config $config;

    /**
     * @var Manager
     */
    private Manager $moduleManager;

    /**
     * @param Config $config
     * @param Manager $moduleManager
     */
    public function __construct(
        Config $config,
        Manager $moduleManager
    ) {
        $this->config = $config;
        $this->moduleManager = $moduleManager;
    }

    /**
     * @return bool
     */
    public function isPageBuilderEnabled(): bool
    {
        return $this->moduleManager->isEnabled('Magento_PageBuilder')
            && $this->config->isEnabled();
    }
}
