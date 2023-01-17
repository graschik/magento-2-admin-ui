<?php
declare(strict_types=1);

namespace Grasch\AdminUi\Plugin\Magento\Framework\View\Layout\Generic;

use Grasch\AdminUi\Model\JsConfiguration\ModifierInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Element\UiComponentInterface;
use Magento\Framework\View\Layout\Generic;

class ModifyJsConfiguration
{
    /**
     * @var RequestInterface
     */
    private RequestInterface $request;

    /**
     * @var ModifierInterface
     */
    private ModifierInterface $modifier;

    /**
     * @param RequestInterface $request
     * @param ModifierInterface $modifier
     */
    public function __construct(
        RequestInterface $request,
        ModifierInterface $modifier
    ) {
        $this->request = $request;
        $this->modifier = $modifier;
    }

    /**
     * Modify ui components
     *
     * @param Generic $subject
     * @param array $result
     * @param UiComponentInterface $component
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterBuild(
        Generic $subject,
        array $result,
        UiComponentInterface $component
    ): array {
        $modifier = $this->request->getParam('js_modifier');
        if (!$modifier) {
            return $result;
        }

        return $this->modifier->execute(
            $result,
            $component->getContext(),
            $modifier['params'] ?? []
        );
    }
}
