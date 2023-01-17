<?php
declare(strict_types=1);

namespace Grasch\AdminUi\Plugin\Magento\Widget\Block\Adminhtml\Widget\Options;

use Grasch\AdminUi\Model\DecodeComponentValue;
use Magento\Widget\Block\Adminhtml\Widget\Options;

class DecodeComponentValues
{
    /**
     * @var DecodeComponentValue
     */
    private DecodeComponentValue $decodeComponentValue;

    /**
     * @param DecodeComponentValue $decodeComponentValue
     */
    public function __construct(
        DecodeComponentValue $decodeComponentValue
    ) {
        $this->decodeComponentValue = $decodeComponentValue;
    }

    /**
     * Decode component value
     *
     * @param Options $subject
     * @param mixed $result
     * @param string $key
     * @return array|mixed
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGetData(
        Options $subject,
        $result,
        string $key
    ) {
        if ($key !== 'widget_values') {
            return $result;
        }

        if (!$result || !is_array($result)) {
            return $result;
        }

        foreach ($result as &$value) {
            $value = $this->decodeComponentValue->execute($value);
        }

        return $result;
    }
}
