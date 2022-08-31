<?php
declare(strict_types=1);

namespace Grasch\AdminUi\Model\Widget;

use Grasch\AdminUi\Component\Widget\Form as WidgetForm;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\UiComponentFactory as BaseUiComponentFactory;
use Magento\Framework\View\Element\UiComponentInterface;
use Magento\Ui\Component\Form;

class UiComponentFactory extends BaseUiComponentFactory
{
    /**
     * @param $identifier
     * @param $name
     * @param array $arguments
     * @return UiComponentInterface
     * @throws LocalizedException
     */
    public function create(
        $identifier,
        $name = null,
        array $arguments = []
    ): UiComponentInterface {
        $resultComponent = parent::create($identifier, $name, $arguments);

        if ($resultComponent->getComponentName() !== Form::NAME) {
            return $resultComponent;
        }

        return $this->objectManager->create(
            WidgetForm::class,
            [
                'context' => $resultComponent->getContext(),
                'components' => $resultComponent->getChildComponents(),
                'data' => $resultComponent->getData(),
                'metadata' => $arguments['metadata']
            ]
        );
    }
}
