<?php
declare(strict_types=1);

namespace Grasch\AdminUi\Block\Widget;

use Magento\Widget\Block\BlockInterface;

class ExampleOfUIComponents extends AbstractWidget implements BlockInterface
{
    /**
     * Render block HTML
     *
     * @return string
     */
    protected function _toHtml(): string
    {
        $data = $this->getData('component_data');
        // phpcs:ignore Magento2.Functions.DiscouragedFunction
        print_r($data);

        return '';
    }
}
