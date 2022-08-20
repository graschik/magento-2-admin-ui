<?php
declare(strict_types=1);

namespace Grasch\AdminUi\Model\JsConfiguration;

use Magento\Framework\View\Element\UiComponent\ContextInterface;

class ModifierComposite implements ModifierInterface
{
    /**
     * @var ModifierInterface[]
     */
    private array $modifiers;

    /**
     * @param ModifierInterface[] $modifiers
     */
    public function __construct(
        array $modifiers = []
    ) {
        $this->modifiers = $modifiers;
    }

    /**
     * @param array $configuration
     * @param ContextInterface $context
     * @param array $params
     * @return array
     */
    public function execute(
        array $configuration,
        ContextInterface $context,
        array $params = []
    ): array {
        foreach ($this->modifiers as $modifier) {
            $configuration = $modifier->execute(
                $configuration,
                $context,
                $params
            );
        }

        return $configuration;
    }
}
