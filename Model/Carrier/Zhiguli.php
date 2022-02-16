<?php

/**
 * @by ProfStep, inc. 16.02.2022
 * @website: https://profstep.com
 **/

declare(strict_types=1);

namespace ProfStep\Shipment\Model\Carrier;


use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory;
use Magento\Quote\Model\Quote\Address\RateResult\MethodFactory;
use Magento\Quote\Model\Quote\Item;
use Magento\Shipping\Model\Rate\Result;
use Magento\Shipping\Model\Rate\ResultFactory;
use Psr\Log\LoggerInterface;

class Zhiguli extends \Magento\Shipping\Model\Carrier\AbstractCarrier implements \Magento\Shipping\Model\Carrier\CarrierInterface
{

    protected $_code = 'zhiguli';

    private ResultFactory $resultFactory;
    private MethodFactory $rateMethodFactory;

    /**
     * Zhiguli constructor.
     *
     * @param ScopeConfigInterface $scopeConfig
     * @param ErrorFactory $rateErrorFactory
     * @param LoggerInterface $logger
     * @param ResultFactory $resultFactory
     * @param MethodFactory $rateMethodFactory
     * @param array $data
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        ErrorFactory $rateErrorFactory,
        LoggerInterface $logger,
        ResultFactory $resultFactory,
        MethodFactory $rateMethodFactory,
        array $data = []
    ) {
        parent::__construct($scopeConfig, $rateErrorFactory, $logger, $data);
        $this->resultFactory = $resultFactory;
        $this->rateMethodFactory = $rateMethodFactory;
    }

    /**
     * Collect and get rates.
     *
     * @param RateRequest $request
     *
     * @return Result|bool
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function collectRates(RateRequest $request)
    {
        if (!$this->getConfigFlag('active')) {
            return false;
        }

        $result = $this->resultFactory->create();

        $shippingPrice = $this->getShippingPrice($request);

        if ($shippingPrice !== false) {
            $method = $this->createResultMethod($shippingPrice);
            $result->append($method);
        }

        return $result;
    }

    /**
     * Creates result method
     *
     * @param int|float $shippingPrice
     *
     * @return \Magento\Quote\Model\Quote\Address\RateResult\Method
     */
    private function createResultMethod($shippingPrice)
    {
        $method = $this->rateMethodFactory->create();

        $method->setCarrier('zhiguli');
        $method->setCarrierTitle($this->getConfigData('title'));

        $method->setMethod('zhiguli');
        $method->setMethodTitle($this->getConfigData('name'));

        $method->setPrice($shippingPrice);
        $method->setCost($shippingPrice);
        return $method;
    }

    /**
     * @inheritDoc
     */
    public function getAllowedMethods(): array
    {
        return [$this->_code => $this->getConfigData('name')];
    }

    /**
     * @param \Magento\Quote\Model\Quote\Address\RateRequest $request
     *
     * @return int|float
     */
    public function getShippingPrice(RateRequest $request)
    {
        $price = 0;

        $items = $request->getAllItems();

        /** @var Item $item */
        foreach ($items as $item) {
            $price += $item->getPrice() * .3;
        }

        return $price;
    }
}
