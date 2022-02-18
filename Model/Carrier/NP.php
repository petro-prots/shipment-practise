<?php

/**
 * @by ProfStep, inc. 16.02.2022
 * @website: https://profstep.com
 **/

declare(strict_types=1);

namespace ProfStep\Shipment\Model\Carrier;

use Magento\CatalogInventory\Api\StockRegistryInterface;
use Magento\Directory\Helper\Data;
use Magento\Directory\Model\CountryFactory;
use Magento\Directory\Model\CurrencyFactory;
use Magento\Directory\Model\RegionFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Xml\Security;
use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory;
use Magento\Quote\Model\Quote\Address\RateResult\Method;
use Magento\Quote\Model\Quote\Address\RateResult\MethodFactory;
use Magento\Quote\Model\Quote\Item;
use Magento\Shipping\Model\Carrier\AbstractCarrierOnline;
use Magento\Shipping\Model\Carrier\CarrierInterface;
use Magento\Shipping\Model\Rate\Result;
use Magento\Shipping\Model\Rate\ResultFactory;
use Magento\Shipping\Model\Simplexml\ElementFactory;
use Magento\Shipping\Model\Tracking\Result\StatusFactory;
use ProfStep\Shipment\Api\Data\NPInterface;
use ProfStep\Shipment\Api\Data\NpRequestResultInterfaceFactory;
use ProfStep\Shipment\Model\NP as NPModel;
use ProfStep\Shipment\Model\ResourceModel\NP as NPResource;
use ProfStep\Shipment\Model\ResourceModel\NP\TrackNumberManager;
use Psr\Log\LoggerInterface;

class NP extends AbstractCarrierOnline implements CarrierInterface
{

    protected $_code = 'np';

    private ResultFactory $resultFactory;
    private MethodFactory $rateMethodFactory;
    private NPModel $np;
    private NpRequestResultInterfaceFactory $requestResultFactory;
    private NPResource $npResource;
    private TrackNumberManager $trackNumberManager;

    /**
     * NP constructor.
     *
     * @param ScopeConfigInterface $scopeConfig
     * @param ErrorFactory $rateErrorFactory
     * @param LoggerInterface $logger
     * @param Security $xmlSecurity
     * @param ElementFactory $xmlElFactory
     * @param ResultFactory $rateFactory
     * @param MethodFactory $rateMethodFactory
     * @param \Magento\Shipping\Model\Tracking\ResultFactory $trackFactory
     * @param \Magento\Shipping\Model\Tracking\Result\ErrorFactory $trackErrorFactory
     * @param StatusFactory $trackStatusFactory
     * @param RegionFactory $regionFactory
     * @param CountryFactory $countryFactory
     * @param CurrencyFactory $currencyFactory
     * @param Data $directoryData
     * @param StockRegistryInterface $stockRegistry
     * @param ResultFactory $resultFactory
     * @param NPModel $np
     * @param NpRequestResultInterfaceFactory $requestResultFactory
     * @param NPResource $npResource
     * @param TrackNumberManager $trackNumberManager
     * @param array $data
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        ErrorFactory $rateErrorFactory,
        LoggerInterface $logger,
        Security $xmlSecurity,
        ElementFactory $xmlElFactory,
        ResultFactory $rateFactory,
        MethodFactory $rateMethodFactory,
        \Magento\Shipping\Model\Tracking\ResultFactory $trackFactory,
        \Magento\Shipping\Model\Tracking\Result\ErrorFactory $trackErrorFactory,
        StatusFactory $trackStatusFactory,
        RegionFactory $regionFactory,
        CountryFactory $countryFactory,
        CurrencyFactory $currencyFactory,
        Data $directoryData,
        StockRegistryInterface $stockRegistry,
        ResultFactory $resultFactory,
        NPModel $np,
        NpRequestResultInterfaceFactory $requestResultFactory,
        NPResource $npResource,
        TrackNumberManager $trackNumberManager,
        array $data = []
    ) {
        parent::__construct(
            $scopeConfig,
            $rateErrorFactory,
            $logger,
            $xmlSecurity,
            $xmlElFactory,
            $rateFactory,
            $rateMethodFactory,
            $trackFactory,
            $trackErrorFactory,
            $trackStatusFactory,
            $regionFactory,
            $countryFactory,
            $currencyFactory,
            $directoryData,
            $stockRegistry,
            $data
        );

        $this->resultFactory = $resultFactory;
        $this->rateMethodFactory = $rateMethodFactory;
        $this->np = $np;
        $this->requestResultFactory = $requestResultFactory;
        $this->npResource = $npResource;
        $this->trackNumberManager = $trackNumberManager;
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
     * @param RateRequest $request
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

    /**
     * Creates result method
     *
     * @param int|float $shippingPrice
     *
     * @return Method
     */
    private function createResultMethod($shippingPrice)
    {
        $method = $this->rateMethodFactory->create();

        $method->setCarrier('np');
        $method->setCarrierTitle($this->getConfigData('title'));

        $method->setMethod('np');
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

    protected function _doShipmentRequest(DataObject $request)
    {
        $result = $this->requestResultFactory->create();
        $shipment = $request->getOrderShipment();
        $order = $shipment->getOrder();
        $customerId = $order->getCustomerId();

        /** @var NPInterface $npInstance */
        $npInstance = $this->npResource->load($this->np, $customerId, "customer_id");

        if (!$npInstance->getId()) {
            $npInstance->setTrackNumber($this->trackNumberManager->getNumber());
            $npInstance->setCustomerId($customerId);

            //Instance is currently created so does not already exist for sure
            try {
                $this->npResource->save($npInstance);
            } catch (AlreadyExistsException $exception) {}
        }
        $result->setTrackingNumber($npInstance->getTrackNumber());
        $result->setShippingLabelContent("300000" . $npInstance->getCustomerId());

        return $result;
    }

    /**
     * Sets All countries as available for delivery
     *
     * @param DataObject $request
     *
     * @return bool
     */
    public function checkAvailableShipCountries(DataObject $request)
    {
        return true;
    }

    /**
     * Sets shipment method as valid
     *
     * @param DataObject $request
     *
     * @return bool
     */
    public function processAdditionalValidation(DataObject $request)
    {
        return true;
    }
}
