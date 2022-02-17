<?php

/**
 * @by ProfStep, inc. 17.02.2022
 * @website: https://profstep.com
 **/

declare(strict_types=1);

namespace ProfStep\Shipment\Api\Data;

interface NPInterface
{
    /**
     * Gets Customer ID.
     *
     * @return int
     */
    public function getCustomerId(): int;

    /**
     * Sets Customer ID.
     *
     * @param int - customer id.
     *
     * @return void
     */
    public function setCustomerId(int $customerId);

    /**
     * Gets Track Number.
     *
     * @return string
     */
    public function getTrackNumber(): string;

    /**
     * Sets Track Number.
     *
     * @param string - track number.
     *
     * @return void
     */
    public function setTrackNumber(string $trackNumber);
}
