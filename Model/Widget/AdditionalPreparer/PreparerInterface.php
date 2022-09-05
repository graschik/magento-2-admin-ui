<?php
declare(strict_types=1);

namespace Grasch\AdminUi\Model\Widget\AdditionalPreparer;

use Magento\Framework\View\Element\UiComponentInterface;

interface PreparerInterface
{
    /**
     * @param UiComponentInterface $component
     * @return mixed
     */
    public function prepare(UiComponentInterface $component);
}
