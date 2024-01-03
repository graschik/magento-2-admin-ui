<?php
declare(strict_types=1);

namespace Grasch\AdminUi\Model\Widget\AdditionalPreparer;

use Grasch\AdminUi\Model\Base64;
use Magento\Framework\View\Element\UiComponentInterface;
use Magento\PageBuilder\Model\State as PageBuilderState;

class WysiwygPreparer implements PreparerInterface
{
    /**
     * @var Base64
     */
    private Base64 $base64;

    /**
     * @var PageBuilderState
     */
    private PageBuilderState $pageBuilderState;

    /**
     * @param Base64 $base64
     * @param PageBuilderState $pageBuilderState
     */
    public function __construct(
        Base64 $base64,
        PageBuilderState $pageBuilderState
    ) {
        $this->base64 = $base64;
        $this->pageBuilderState = $pageBuilderState;
    }

    /**
     * Prepare wysiwyg
     *
     * @param UiComponentInterface $component
     * @return mixed|void
     */
    public function prepare(UiComponentInterface $component)
    {
        $config = $component->getData('config');

        $config['content'] = $this->base64->encode($config['content']);

        $wysiwygConfigData = isset($config['wysiwygConfigData']) ? $config['wysiwygConfigData'] : [];
        $isEnablePageBuilder = isset($wysiwygConfigData['is_pagebuilder_enabled'])
            && !$wysiwygConfigData['is_pagebuilder_enabled']
            || false;
        if (!$this->pageBuilderState->isPageBuilderInUse($isEnablePageBuilder)) {
            $config['component'] = 'Grasch_AdminUi/js/widget/form/element/page-builder/wysiwyg';
        } else {
            $config['component'] = 'Grasch_AdminUi/js/widget/form/element/wysiwyg';
        }

        $component->setData('config', $config);
    }
}
