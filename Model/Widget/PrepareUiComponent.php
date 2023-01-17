<?php
declare(strict_types=1);

namespace Grasch\AdminUi\Model\Widget;

use Grasch\AdminUi\Model\Widget\AdditionalPreparer\PreparerInterface;
use Psr\Log\InvalidArgumentException;
use Magento\Framework\View\Element\UiComponentInterface;

class PrepareUiComponent
{
    /**
     * @var array
     */
    private array $additionalPreparers;

    /**
     * @param array $additionalPreparers
     */
    public function __construct(
        array $additionalPreparers = []
    ) {
        $this->additionalPreparers = $additionalPreparers;
    }

    /**
     * Prepare ui component
     *
     * @param UiComponentInterface $component
     * @return void
     */
    public function execute(UiComponentInterface $component): void
    {
        $childComponents = $component->getChildComponents();
        if (!empty($childComponents)) {
            foreach ($childComponents as $child) {
                $this->execute($child);
            }
        }
        $component->prepare();

        $componentName = !empty($component->getData()['formElement'])
            ? $component->getData()['formElement']
            : $component->getComponentName();

        if (isset($this->additionalPreparers[$componentName])) {
            /** @var PreparerInterface $preparer */
            foreach ($this->additionalPreparers[$componentName] as $preparer) {
                if (!$preparer instanceof PreparerInterface) {
                    throw new InvalidArgumentException(
                        '$preparer must implement ' . PreparerInterface::class
                    );
                }
                $preparer->prepare($component);
            }
        }
    }
}
