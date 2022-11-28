<?php
/**
 * Copyright © InComm, Inc. All rights reserved.
 */
declare(strict_types=1);

namespace Grasch\AdminUi\Model;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Serialize\SerializerInterface;
use Psr\Log\LoggerInterface;

class DecodeComponentValue
{
    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * @var Base64
     */
    private Base64 $base64;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @param SerializerInterface $serializer
     * @param Base64 $base64
     * @param LoggerInterface $logger
     */
    public function __construct(
        SerializerInterface $serializer,
        Base64 $base64,
        LoggerInterface $logger
    ) {
        $this->serializer = $serializer;
        $this->base64 = $base64;
        $this->logger = $logger;
    }

    /**
     * @param string $value
     * @return array
     */
    public function execute(string $value): array
    {
        if (preg_match('/encodedComponentsData\|.*/', $value)) {
            $value = preg_replace('/encodedComponentsData\|/', '', $value);
            try {
                $value = $this->base64->decode($value);
                $value = $this->serializer->unserialize($value);
            } catch (LocalizedException $e) {
                $this->logger->error($e->getMessage());
            };
        }

        return $value;
    }
}
