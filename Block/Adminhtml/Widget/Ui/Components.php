<?php
declare(strict_types=1);

namespace Grasch\AdminUi\Block\Adminhtml\Widget\Ui;

use Grasch\AdminUi\Model\Widget\MetadataInterface;
use Grasch\AdminUi\Model\Widget\MetadataInterfaceFactory;
use Grasch\AdminUi\Model\Widget\PrepareUiComponent;
use Grasch\AdminUi\Model\Widget\UiComponentFactory;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Backend\Block\Template;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\View\Element\UiComponent\ContextFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Layout\Data\Structure;
use Magento\Framework\View\Layout\Generator\Structure as StructureGenerator;

class Components extends Widget
{
    /**
     * @var SerializerInterface
     */
    protected SerializerInterface $serializer;

    /**
     * @var ContextFactory
     */
    protected ContextFactory $contextFactory;

    /**
     * @var ContextInterface|null
     */
    protected ?ContextInterface $context = null;

    /**
     * @var UiComponentFactory
     */
    protected UiComponentFactory $uiComponentFactory;

    /**
     * @var Structure
     */
    protected Structure $structure;

    /**
     * @var StructureGenerator
     */
    protected StructureGenerator $structureGenerator;

    /**
     * @var MetadataInterfaceFactory
     */
    protected MetadataInterfaceFactory $metadataFactory;

    /**
     * @var PrepareUiComponent
     */
    protected PrepareUiComponent $prepareUiComponent;

    /**
     * @param Context $context
     * @param SerializerInterface $serializer
     * @param ContextFactory $contextFactory
     * @param UiComponentFactory $uiComponentFactory
     * @param StructureGenerator $structureGenerator
     * @param Structure $structure
     * @param MetadataInterfaceFactory $metadataFactory
     * @param PrepareUiComponent $prepareUiComponent
     * @param array $data
     * @throws LocalizedException
     */
    public function __construct(
        Template\Context $context,
        SerializerInterface $serializer,
        ContextFactory $contextFactory,
        UiComponentFactory $uiComponentFactory,
        StructureGenerator $structureGenerator,
        Structure $structure,
        MetadataInterfaceFactory $metadataFactory,
        PrepareUiComponent $prepareUiComponent,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->serializer = $serializer;
        $this->contextFactory = $contextFactory;
        $this->uiComponentFactory = $uiComponentFactory;
        $this->structure = $structure;
        $this->structureGenerator = $structureGenerator;
        $this->metadataFactory = $metadataFactory;
        $this->prepareUiComponent = $prepareUiComponent;

        if (empty($this->getNamespace())) {
            throw new LocalizedException(__('`namespace` is required.'));
        }
    }

    /**
     * @throws LocalizedException
     */
    public function prepareElementHtml(AbstractElement $element): AbstractElement
    {
        $component = $this->uiComponentFactory->create(
            $this->getNamespace(),
            null,
            [
                'context' => $this->getContext(),
                'metadata' => $this->generateMetadata($element),
                'structure' => $this->structure
            ]
        );
        $this->prepareUiComponent->execute($component);
        $configuration = $this->structureGenerator->generate($component);

        $element->setData('after_element_html', $this->render($configuration));
        $element->setData('value', '');
        $element->setType('hidden');

        return $element;
    }

    /**
     * @param AbstractElement $element
     * @return MetadataInterface
     */
    protected function generateMetadata(AbstractElement $element): MetadataInterface
    {
        $value = $element->getValue();
        if (!is_array($value)) {
            $value = [];
        }

        /** @var MetadataInterface $metadata */
        $metadata = $this->metadataFactory->create();
        $metadata->setFormData($value);
        $metadata->setSyncFieldName($element->getName());

        return $metadata;
    }

    /**
     * @return ContextInterface
     * @throws LocalizedException
     */
    protected function getContext(): ContextInterface
    {
        if (!$this->context) {
            $this->context = $this->contextFactory->create([
                'namespace' => $this->getNamespace(),
                'pageLayout' => $this->getLayout()
            ]);
        }

        return $this->context;
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

        return <<<EOT
<div data-bind="scope: '$scope'"
     data-scope="$scope"
     class="entry-edit form-inline widget-ui-components admin__fieldset"
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
}
