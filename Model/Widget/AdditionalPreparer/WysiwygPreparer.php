<?php
declare(strict_types=1);

namespace Grasch\AdminUi\Model\Widget\AdditionalPreparer;

use Grasch\AdminUi\Model\Base64;
use Magento\Framework\View\Element\UiComponentInterface;

class WysiwygPreparer implements PreparerInterface
{
    /**
     * @var Base64
     */
    private Base64 $base64;

    /**
     * @param Base64 $base64
     */
    public function __construct(
        Base64 $base64
    ) {
        $this->base64 = $base64;
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
        $config['component'] = 'Grasch_AdminUi/js/widget/form/element/wysiwyg';
        $component->setData('config', $config);
    }
}
