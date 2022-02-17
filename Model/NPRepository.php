<?php

/**
 * @by ProfStep, inc. 17.02.2022
 * @website: https://profstep.com
 **/

declare(strict_types=1);

namespace ProfStep\Shipment\Model;

use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Magento\Framework\EntityManager\HydratorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Reflection\DataObjectProcessor;
use ProfStep\Shipment\Api\NPRepositoryInterface;
use ProfStep\Shipment\Model\ResourceModel\NP as NPResource;
use ProfStep\Shipment\Model\ResourceModel\NP\Collection as NPCollectionFactory;

class NPRepository implements NPRepositoryInterface
{
    /** @var NPResource */
    protected NPResource $resource;

    /** @var NPFactory */
    protected NPFactory $npFactory;

    /** @var NPCollectionFactory */
    protected NPCollectionFactory $npCollectionFactory;

    /** @var SearchResultsInterfaceFactory */
    protected SearchResultsInterfaceFactory $searchResultsFactory;

    /** @var DataObjectHelper */
    protected DataObjectHelper $dataObjectHelper;

    /** @var DataObjectProcessor */
    protected DataObjectProcessor $dataObjectProcessor;

    /** @var CollectionProcessorInterface */
    private CollectionProcessorInterface $collectionProcessor;

    /** @var HydratorInterface */
    private HydratorInterface $hydrator;

    /**
     * NPRepository constructor.
     *
     * @param NPResource $resource
     * @param NPFactory $rajaUserFactory
     * @param NPCollectionFactory $rajaCollectionFactory
     * @param SearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param CollectionProcessorInterface $collectionProcessor
     * @param HydratorInterface $hydrator
     */
    public function __construct(
        NPResource $resource,
        NPFactory $rajaUserFactory,
        NPCollectionFactory $rajaCollectionFactory,
        SearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        CollectionProcessorInterface $collectionProcessor,
        HydratorInterface $hydrator
    ) {
        $this->resource = $resource;
        $this->npFactory = $rajaUserFactory;
        $this->npCollectionFactory = $rajaCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->collectionProcessor = $collectionProcessor;
        $this->hydrator = $hydrator;
    }

    /**
     * Saves NP model.
     *
     * @param NP $npModel
     * @return NP
     * @throws CouldNotSaveException if failed to save via resource model
     * @throws NoSuchEntityException if failed to fetch entity from db
     */
    public function save(NP $npModel): NP
    {
        if ($npModel->getId() && $npModel instanceof NP && !$npModel->getOrigData()) {
            $npModel = $this->hydrator->hydrate($this->getById($npModel->getId()), $this->hydrator->extract($npModel));
        }

        try {
            $this->resource->save($npModel);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }

        return $npModel;
    }

    /**
     * Gets NP instance by id
     *
     * @param string $npId
     *
     * @return NP
     *
     * @throws NoSuchEntityException
     */
    public function getById($npId): NP
    {
        $npCollection = $this->npCollectionFactory->create();
        $npCollection->addFieldToFilter("id", $npId);

        $np = $npCollection->load()->getFirstItem();

        if (!$np->getId()) {
            throw new NoSuchEntityException(__('The np instance with the "%1" ID doesn\'t exist.', $npId));
        }

        return $np;
    }

    /**
     * Gets NP instance by track number
     *
     * @param string $trackNumber
     *
     * @return NP
     *
     * @throws NoSuchEntityException
     */
    public function getByTrackNumber(string $trackNumber): NP
    {
        $npCollection = $this->npCollectionFactory->create();
        $npCollection->addFieldToFilter("track_number", $trackNumber);

        $np = $npCollection->load()->getFirstItem();

        if (!$np->getId()) {
            throw new NoSuchEntityException(__('The np instance with the "%1" ID doesn\'t exist.', $trackNumber));
        }

        return $np;
    }

    /**
     * Gets list of tracking data by search criteria.
     *
     * @param SearchCriteriaInterface $criteria
     *
     * @return SearchResultsInterface
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function getList(SearchCriteriaInterface $criteria): SearchResultsInterface
    {
        $collection = $this->npCollectionFactory->create();

        $this->collectionProcessor->process($criteria, $collection);

        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }

    /**
     * Deletes model from DB
     *
     * @param NP $npModel
     *
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(NP $npModel): bool
    {
        try {
            $this->resource->delete($npModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }

    /**
     * Deletes by NP id
     *
     * @param string $npId
     *
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById($npId): bool
    {
        return $this->delete($this->getById($npId));
    }
}
