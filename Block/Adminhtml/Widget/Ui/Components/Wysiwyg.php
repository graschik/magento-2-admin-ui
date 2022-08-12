<?php
declare(strict_types=1);

namespace Grasch\AdminUi\Block\Adminhtml\Widget\Ui\Components;

use Magento\Framework\View\Layout\Generator\Structure;
use Magento\Ui\Component\Form\Element\Wysiwyg as BaseWysiwyg;
use Magento\Ui\Component\Form\Element\WysiwygFactory;

class Wysiwyg extends AbstractComponent
{
    /**
     * @var Structure
     */
    private Structure $structure;

    /**
     * @var WysiwygFactory
     */
    private WysiwygFactory $wysiwygFactory;

    /**
     * @param string $name
     * @param Structure $structure
     * @param array $dataConfig
     */
    public function __construct(
        string $name,
        Structure $structure,
        WysiwygFactory $wysiwygFactory,
        array $dataConfig = []
    ) {
        parent::__construct($name, $dataConfig);

        $this->structure = $structure;
        $this->wysiwygFactory = $wysiwygFactory;
    }

    /**
     * @return array
     */
    public function buildComponent(): array
    {
        /** @var BaseWysiwyg $component */
        $component = $this->wysiwygFactory->create([
            'context' => $this->getContext(),
            'data' => [
                'config' => [
                    'component' => 'Grasch_AdminUi/js/widget/form/element/wysiwyg',
                    'template' => 'ui/content/content',
                    'dataScope' => $this->getName(),
                ],
                'name' => $this->getName()
            ],
            'config' => [
                'wysiwyg' => true
            ]
        ]);
        $component->prepare();
        $component->setData('layout', ['type' => 'custom_generic']);

        $result = $this->structure->generate($component)[$this->getName()];

        $result['config']['content'] = base64_encode($result['config']['content']);

        return $result;
    }
}
