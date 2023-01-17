<?php
declare(strict_types=1);

namespace Grasch\AdminUi\Model\JsConfiguration\Modifiers;

use Magento\Framework\Stdlib\ArrayManager;
use Magento\Framework\View\Element\UiComponent\ContextInterface;

abstract class AbstractModifier
{
    /**
     * @var ArrayManager
     */
    protected ArrayManager $_arrayManager;

    /**
     * @param ArrayManager $arrayManager
     */
    public function __construct(
        ArrayManager $arrayManager
    ) {
        $this->_arrayManager = $arrayManager;
    }

    /**
     * Get path to main component
     *
     * @param ContextInterface $context
     * @return string
     */
    public function getPathToMainComponent(ContextInterface $context): string
    {
        return sprintf(
            'components/%s/children/%s',
            $context->getNamespace(),
            $context->getNamespace()
        );
    }
}
