<?php

/**
 * @by ProfStep, inc. 16.02.2022
 * @website: https://profstep.com
 **/

declare(strict_types=1);

namespace ProfStep\Shipment\Model\ResourceModel\NP;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use ProfStep\Shipment\Model\NP as Model;
use ProfStep\Shipment\Model\ResourceModel\NP as ResourceModel;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'nova_poshta_shipment_collection';

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init(Model::class, ResourceModel::class);
    }
}
