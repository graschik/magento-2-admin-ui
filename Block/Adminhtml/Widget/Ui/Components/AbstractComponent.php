<?php
declare(strict_types=1);

namespace Grasch\AdminUi\Block\Adminhtml\Widget\Ui\Components;

use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\View\Element\UiComponent\ContextInterface;

abstract class AbstractComponent implements ComponentInterface
{
    /**
     * @var AbstractElement|null
     */
    protected ?AbstractElement $element = null;

    /**
     * @var ContextInterface|null
     */
    protected ?ContextInterface $context = null;

    /**
     * @var string
     */
    protected string $name;

    /**
     * @var array
     */
    protected array $dataConfig;

    /**
     * @param string $name
     * @param array $dataConfig
     */
    public function __construct(
        string $name,
        array $dataConfig = []
    ) {
        $this->name = $name;
        $this->dataConfig = $dataConfig;
    }

    /**
     * @param ContextInterface $context
     * @return ComponentInterface
     */
    public function setContext(ContextInterface $context): ComponentInterface
    {
        $this->context = $context;

        return $this;
    }

    /**
     * @return ContextInterface
     */
    public function getContext(): ContextInterface
    {
        return $this->context;
    }

    /**
     * @param AbstractElement $element
     * @return ComponentInterface
     */
    public function setElement(AbstractElement $element): ComponentInterface
    {
        $this->element = $element;

        return $this;
    }

    /**
     * @return AbstractElement
     */
    public function getElement(): AbstractElement
    {
        return $this->element;
    }

    /**
     * @return array
     */
    public function getDataConfig(): array
    {
        return $this->dataConfig;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
