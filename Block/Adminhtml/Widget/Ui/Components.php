<?php
declare(strict_types=1);

namespace Grasch\AdminUi\Block\Adminhtml\Widget\Ui;

use Grasch\AdminUi\Block\Adminhtml\Widget\Ui\Components\ComponentInterface;
use Magento\Backend\Block\Widget;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Backend\Block\Template;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\Stdlib\ArrayManager;
use Magento\Framework\View\Element\UiComponent\ContextFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;

class Components extends Widget
{
    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * @var ObjectManagerInterface
     */
    private ObjectManagerInterface $objectManager;

    /**
     * @var ContextFactory
     */
    private ContextFactory $contextFactory;

    /**
     * @var ContextInterface|null
     */
    private ?ContextInterface $context = null;

    /**
     * @var ArrayManager
     */
    private ArrayManager $arrayManager;

    /**
     * @param Template\Context $context
     * @param SerializerInterface $serializer
     * @param ObjectManagerInterface $objectManager
     * @param ContextFactory $contextFactory
     * @param ArrayManager $arrayManager
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        SerializerInterface $serializer,
        ObjectManagerInterface $objectManager,
        ContextFactory $contextFactory,
        ArrayManager $arrayManager,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->serializer = $serializer;
        $this->objectManager = $objectManager;
        $this->contextFactory = $contextFactory;
        $this->arrayManager = $arrayManager;
    }

    /**
     * @throws LocalizedException
     */
    public function prepareElementHtml(AbstractElement $element): AbstractElement
    {
        $type = $element->getForm()->getParent()->getWidgetType();
        $this->setData('type', $type);

        $componentInstances = $this->createComponentInstances(
            $this->getChildren(),
            $element,
            $this->getContext()
        );

        $components = [];

        foreach ($componentInstances as $componentInstance) {
            $components[$componentInstance->getName()] = $componentInstance->buildComponent();
        }

        $wrapper = $this->generateWrapperComponent();
        $wrapper['children'] = array_merge(
            $components,
            ['data_input' => $this->buildInputComponent($element)]
        );

        $configuration = [
            'types' => $this->getComponentsDefinitions(),
            'components' => [
                $this->getNamespace() => [
                    'children' => array_merge(
                        [$this->getNamespace() => $wrapper],
                        $this->prepareDataSource($element)
                    )
                ]
            ]
        ];

        $element->setData('after_element_html', $this->render($configuration));
        $element->setData('value', '');

        return $element;
    }

    public function generateWrapperComponent(): array
    {
        return [
            'type' => $this->getNamespace(),
            'dataScope' => 'data',
            'config' => [
                'componentType' => 'container',
                'component' => 'uiComponent'
            ],
        ];
    }

    /**
     * @return array
     */
    protected function buildInputComponent(AbstractElement $element): array
    {
        return [
            'type' => 'valueComponent',
            'config' => [
                'component' => 'Grasch_AdminUi/js/widget/form/element/abstract',
                'template' => 'Grasch_AdminUi/widget/form/element/hidden',
                'customInputName' => $element->getName()
            ]
        ];
    }

    /**
     * @param array $components
     * @param AbstractElement $element
     * @param ContextInterface $context
     * @return ComponentInterface[]
     * @throws LocalizedException
     */
    protected function createComponentInstances(
        array $components,
        AbstractElement $element,
        ContextInterface $context
    ): array {
        $result = [];

        foreach ($components as $componentName => $component) {
            /** @var ComponentInterface $componentInstance */
            $componentInstance = $this->objectManager->create(
                $component['componentClassName'],
                [
                    'name' => $componentName,
                    'dataConfig' => $component['dataConfig'] ?? []
                ]
            );

            if (!$componentInstance instanceof ComponentInterface) {
                throw new LocalizedException(
                    __('Instance of the %1 is expected.', ComponentInterface::class)
                );
            }

            $componentInstance->setContext($context);
            $componentInstance->setElement($element);

            $result[] = $componentInstance;
        }

        return $result;
    }

    /**
     * @return array[]
     */
    protected function getComponentsDefinitions(): array
    {
        $dataSourceName = sprintf(
            '%s.%s_data_source',
            $this->getNamespace(),
            $this->getNamespace()
        );

        return [
            $this->getNamespace() => [
                'deps' => [$dataSourceName],
                'provider' => $dataSourceName
            ]
        ];
    }

    /**
     * @param array $additionalData
     * @return array[]
     */
    protected function prepareDataSource(AbstractElement $element): array
    {
        $dataSource = [
            $this->getNamespace() . '_data_source' => [
                'type' => 'dataSource',
                'name' => $this->getNamespace() . '_data_source',
                'dataScope' => $this->getNamespace(),
                'config' => [
                    'component' => 'Magento_Ui/js/form/provider',
                    'params' => [
                        'namespace' => $this->getNamespace()
                    ]
                ]
            ]
        ];

        if (!empty($element->getValue())) {
            $dataSource = $this->arrayManager->merge(
                $this->getNamespace() . '_data_source/config',
                $dataSource,
                ['data' => $element->getValue()]
            );
        }

        return $dataSource;
    }

    /**
     * @param array $configuration
     * @return string
     */
    public function render(array $configuration): string
    {
        $wrappedContent = $this->wrapContent(
            $this->serializer->serialize($configuration)
        );
        $scope = $this->getScope();
        $type = $this->getData('type');

        return <<<EOT
<div data-bind="scope: '$scope'"
     data-type="$type"
     data-scope="$scope"
     class="entry-edit form-inline widget-ui-components"
 >
    <!-- ko template: getTemplate() --><!-- /ko -->
</div>
$wrappedContent
EOT;
    }

    /**
     * @return string
     */
    public function getScope(): string
    {
        return $this->getNamespace() . '.' . $this->getNamespace();
    }

    /**
     * @param string $content
     * @return string
     */
    protected function wrapContent(string $content): string
    {
        return '<components style="display: none" type="text/x-magento-init">'
            . '{"*": {"Magento_Ui/js/core/app": ' . $content . '}}'
            . '</components>';
    }

    /**
     * @return ContextInterface
     * @throws LocalizedException
     */
    protected function getContext(): ContextInterface
    {
        if (!$this->context) {
            $this->context = $this->contextFactory->create([
                'namespace' => 'testnamespace',
                'pageLayout' => $this->getLayout()
            ]);
        }

        return $this->context;
    }
}
