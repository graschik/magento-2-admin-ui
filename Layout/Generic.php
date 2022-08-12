<?php
declare(strict_types=1);

namespace Grasch\AdminUi\Layout;

use Magento\Framework\View\Element\UiComponentInterface;
use Magento\Framework\View\Layout\Generic as BaseGeneric;

class Generic extends BaseGeneric
{
    /**
     * @inheritdoc
     */
    public function build(UiComponentInterface $component): array
    {
        $children = [];
        $this->addChildren($children, $component, $component->getName());

        return $children;
    }
}
