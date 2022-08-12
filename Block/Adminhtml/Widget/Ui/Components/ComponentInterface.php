<?php
declare(strict_types=1);

namespace Grasch\AdminUi\Block\Adminhtml\Widget\Ui\Components;

use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\View\Element\UiComponent\ContextInterface;

interface ComponentInterface
{
    /**
     * @return array
     */
    public function buildComponent(): array;

    /**
     * @param ContextInterface $context
     * @return ComponentInterface
     */
    public function setContext(ContextInterface $context): ComponentInterface;

    /**
     * @return ContextInterface
     */
    public function getContext(): ContextInterface;

    /**
     * @param AbstractElement $element
     * @return ComponentInterface
     */
    public function setElement(AbstractElement $element): ComponentInterface;

    /**
     * @return AbstractElement
     */
    public function getElement(): AbstractElement;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return array
     */
    public function getDataConfig(): array;
}
