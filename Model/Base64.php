<?php
declare(strict_types=1);

namespace Grasch\AdminUi\Model;

use Magento\Framework\Exception\LocalizedException;

class Base64
{
    /**
     * Decode $value
     *
     * @param string $value
     * @return null|string
     * @throws LocalizedException
     * @phpcs:disable Magento2.Functions.DiscouragedFunction
     */
    public function decode(string $value): ?string
    {
        if ($this->isValidBase64($value)) {
            $result = base64_decode($value, true);
            return ($result !== false) ? $result : null;
        }
        throw new LocalizedException(__('Data "%1" is incorrect.', $value));
    }

    /**
     * Encode $value
     *
     * @param string $value
     * @return string
     * @phpcs:disable Magento2.Functions.DiscouragedFunction
     */
    public function encode(string $value): string
    {
        return base64_encode($value);
    }

    /**
     * Validate base64 encoded value
     *
     * @param string $value
     * @return bool
     * @phpcs:disable Magento2.Functions.DiscouragedFunction
     */
    public function isValidBase64(string $value): bool
    {
        $decodedValue = base64_decode($value, true);
        if ($decodedValue === false) {
            return false;
        }

        return base64_encode($decodedValue) === $value;
    }
}
