<?php

/**
 * @by ProfStep, inc. 16.02.2022
 * @website: https://profstep.com
 **/

declare(strict_types=1);

namespace ProfStep\Shipment\Api\Data;

use Exception;

/**
 * Interface for emulated NP request response
 *
 * @package ProfStep\Shipment\Api\Data
 */
interface NpRequestResultInterface
{
    /**
     * Return Tracking Number
     *
     * @return string
     */
    public function getTrackingNumber(): string;

    /**
     * Sets Tracking Number.
     *
     * @param string $trackingNumber
     *
     * @return void
     */
    public function setTrackingNumber(string $trackingNumber): void;

    /**
     * Return Shipping label content.
     *
     * @return string
     */
    public function getShippingLabelContent(): string;

    /**
     * Sets shipping label content.
     *
     * @param string $labelContent
     *
     * @return void
     */
    public function setShippingLabelContent(string $labelContent): void;

    /**
     * Return request errors if exist.
     *
     * @return array
     */
    public function getErrors(): array;

    /**
     * Sets Errors.
     *
     * @param Exception $errors
     *
     * @return void
     */
    public function setErrors(Exception $errors): void;
}
