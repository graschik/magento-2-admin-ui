<?php
declare(strict_types=1);

namespace Grasch\AdminUi\Block\Widget;

use Magento\Widget\Block\BlockInterface;

class ExampleOfUIComponents extends AbstractWidget implements BlockInterface
{
    /**
     * @return string
     */
    protected function _toHtml(): string
    {
        $data = $this->getData('component_data');
        print_r($data);

        return '';
    }
}
