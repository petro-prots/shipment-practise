<?php

/**
 * @by ProfStep, inc. 16.02.2022
 * @website: https://profstep.com
 **/

declare(strict_types=1);

namespace ProfStep\Shipment\Api\Data;


interface NpRequestResultInterface
{
    /**
     * Return Tracking Number
     *
     * @return string
     */
    public function getTrackingNumber(): string;

    /**
     * Return Shipping label content.
     *
     * @return string
     */
    public function getShippingLabelContent(): string;
}
