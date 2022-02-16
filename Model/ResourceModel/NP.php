<?php

/**
 * @by ProfStep, inc. 16.02.2022
 * @website: https://profstep.com
 **/

declare(strict_types=1);

namespace ProfStep\Shipment\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class NP extends AbstractDb
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'nova_poshta_shipment_resource_model';

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init('nova_poshta_shipment', 'id');
        $this->_useIsObjectNew = true;
    }
}
