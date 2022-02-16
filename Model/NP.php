<?php

/**
 * @by ProfStep, inc. 16.02.2022
 * @website: https://profstep.com
 **/

declare(strict_types=1);

namespace ProfStep\Shipment\Model;

use Magento\Framework\Model\AbstractModel;
use ProfStep\Shipment\Model\ResourceModel\NP as ResourceModel;

class NP extends AbstractModel
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
     * @TODO create appropriate interface for model
     * @TODO create NP repository
     */
}
