<?php

/**
 * @by ProfStep, inc. 16.02.2022
 * @website: https://profstep.com
 **/

declare(strict_types=1);

namespace ProfStep\Shipment\Model;


use ProfStep\Shipment\Api\Data\NpRequestResultInterface;

class NpRequestResult implements NpRequestResultInterface
{

    public function __construct(

    ) {
    }

    public function getTrackingNumber(): string
    {

    }

    public function getShippingLabelContent(): string
    {
        // TODO: Implement getShippingLabelContent() method.
    }
}
