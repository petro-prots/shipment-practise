<?php

/**
 * @by ProfStep, inc. 16.02.2022
 * @website: https://profstep.com
 **/

declare(strict_types=1);

namespace ProfStep\Shipment\Model;

use Magento\Framework\Model\AbstractModel;
use ProfStep\Shipment\Api\Data\NPInterface;
use ProfStep\Shipment\Model\ResourceModel\NP as ResourceModel;

/**
 * NP delivery instance.
 *
 * @method int getId()
 * @method void setId(mixed $value)
 *
 * @package ProfStep\Shipment\Model
 */
class NP extends AbstractModel implements NPInterface
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'nova_poshta_shipment_model';

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }

    /**
     * Gets Customer ID.
     *
     * @return int
     */
    public function getCustomerId(): int
    {
        return $this->getData("customer_id");
    }

    /**
     * Sets Customer ID.
     *
     * @param int - customer id.
     *
     * @return void
     */
    public function setCustomerId(int $customerId)
    {
        $this->setData("customer_id", $customerId);
    }

    /**
     * Gets Track Number.
     *
     * @return string
     */
    public function getTrackNumber(): string
    {
        return $this->getData("track_number");
    }

    /**
     * Sets Track Number.
     *
     * @param string - track number.
     *
     * @return void
     */
    public function setTrackNumber(string $trackNumber)
    {
        $this->setData("track_number", $trackNumber);
    }
}
