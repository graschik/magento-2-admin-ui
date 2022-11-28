<?php
declare(strict_types=1);

namespace Grasch\AdminUi\Block\Widget;

use Grasch\AdminUi\Model\DecodeComponentValue;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

abstract class AbstractWidget extends Template
{
    /**
     * @var DecodeComponentValue
     */
    private DecodeComponentValue $decodeComponentValue;

    /**
     * @param Context $context
     * @param DecodeComponentValue $decodeComponentValue
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        DecodeComponentValue $decodeComponentValue,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->decodeComponentValue = $decodeComponentValue;
    }

    /**
     * @param $key
     * @param $index
     * @return array|mixed|string|null
     */
    public function getData($key = '', $index = null)
    {
        $result = parent::getData($key, $index);

        if (!$result || !is_string($result)) {
            return $result;
        }

        return $this->decodeComponentValue->execute($result);
    }
}
