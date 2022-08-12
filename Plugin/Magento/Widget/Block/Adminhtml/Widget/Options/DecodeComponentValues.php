<?php
declare(strict_types=1);

namespace Grasch\AdminUi\Plugin\Magento\Widget\Block\Adminhtml\Widget\Options;

use Grasch\AdminUi\Model\Base64;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Widget\Block\Adminhtml\Widget\Options;
use Psr\Log\LoggerInterface;

class DecodeComponentValues
{
    /**
     * @var Base64
     */
    private Base64 $base64;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * @param Base64 $base64
     * @param LoggerInterface $logger
     * @param SerializerInterface $serializer
     */
    public function __construct(
        Base64 $base64,
        LoggerInterface $logger,
        SerializerInterface $serializer
    ) {
        $this->base64 = $base64;
        $this->logger = $logger;
        $this->serializer = $serializer;
    }

    /**
     * @param Options $subject
     * @param $result
     * @param string $key
     * @return array|mixed
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
            if (preg_match('/encodedComponentsData\|.*/', $value)) {
                $value = preg_replace('/encodedComponentsData\|/', '', $value);
                try {
                    $value = $this->base64->decode($value);
                    $value = $this->serializer->unserialize($value);
                } catch (LocalizedException $e) {
                    $this->logger->error($e->getMessage());
                };
            }
        }

        return $result;
    }
}
