<?php

/**
 * @by ProfStep, inc. 17.02.2022
 * @website: https://profstep.com
 **/

declare(strict_types=1);

namespace ProfStep\Shipment\Model\ResourceModel\NP;


use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Framework\Math\Random;

class TrackNumberManager extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    const TABLE = 'nova_poshta_shipment';

    /**
     * @var string
     */
    protected $_eventPrefix = 'track_number_manager';
    private Random $random;

    public function __construct(
        Context $context,
        Random $random,
        $connectionName = null
    ) {
        parent::__construct($context, $connectionName);
        $this->random = $random;
    }

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init(self::TABLE, 'id');
        $this->_useIsObjectNew = true;
    }

    /**
     * @throws \Magento\Framework\Exception\LocalizedException If framework fails.
     */
    public function getNumber(): string
    {
        do {
            $trackNumber = $this->random->getRandomString(16);

            $connection = $this->getConnection();
            $select = $connection
                ->select()
                ->from(self::TABLE)
                ->where($this->getConnection()->quoteInto("track_number = ?", $trackNumber));

        } while ($this->getConnection()->fetchOne($select));

        return $trackNumber;
    }
}
