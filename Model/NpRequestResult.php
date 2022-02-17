<?php

/**
 * @by ProfStep, inc. 16.02.2022
 * @website: https://profstep.com
 **/

declare(strict_types=1);

namespace ProfStep\Shipment\Model;

use Exception;
use ProfStep\Shipment\Api\Data\NpRequestResultInterface;

/**
 * Class NpRequestResult.
 * Used for NP online shipping method request results
 *
 * @package ProfStep\Shipment\Model
 */
class NpRequestResult implements NpRequestResultInterface
{

    /**
     * Delivery tracking number.
     *
     * @var string
     */
    private string $trackingNumber;

    /**
     * Shipping box label contents.
     *
     * @var string
     */
    private string $shippingLabelContent;

    /**
     * Request errors.
     *
     * @var \Exception[]
     */
    private array $errors;

    /**
     * @return string
     */
    public function getTrackingNumber(): string
    {
        return $this->trackingNumber;
    }

    /**
     * @param string $trackingNumber
     */
    public function setTrackingNumber(string $trackingNumber): void
    {
        $this->trackingNumber = $trackingNumber;
    }

    /**
     * @return string
     */
    public function getShippingLabelContent(): string
    {
        return $this->shippingLabelContent;
    }

    /**
     * @param string $labelContent
     */
    public function setShippingLabelContent(string $labelContent): void
    {
        $this->shippingLabelContent = $labelContent;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @param \Exception $error
     */
    public function setErrors(\Exception $error): void
    {
        $this->errors[] = $error;
    }
}
