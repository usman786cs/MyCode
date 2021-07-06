<?php
/**
 *
 * @category : RLTSquare
 * @Package  : RLTSquare_CustomerGroupPricing
 * @Author   : RLTSquare <support@rltsquare.com>
 * @copyright Copyright 2021 © rltsquare.com All right reserved
 * @license https://rltsquare.com/
 */

namespace RLTSquare\CustomerGroupPricing\Model;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Customer\Model\SessionFactory;
use Magento\Framework\Api\SortOrder;
use RLTSquare\CustomerGroupPricing\Model\CustomerGroupPricing;
use RLTSquare\CustomerGroupPricing\Api\CustomerGroupPricingRepositoryInterface;
use RLTSquare\CustomerGroupPricing\Api\Data\CustomerGroupPricingInterface;
use RLTSquare\CustomerGroupPricing\Model\CustomerGroupPricingFactory;
use RLTSquare\CustomerGroupPricing\Model\ResourceModel\CustomerGroupPricing as CustomerGroupPricingResourceModel;
use RLTSquare\CustomerGroupPricing\Model\ResourceModel\CustomerGroupPricing\Collection;
use RLTSquare\CustomerGroupPricing\Model\ResourceModel\CustomerGroupPricing\CollectionFactory;
use RLTSquare\CustomerGroupPricing\Api\Data\CustomerGroupPricingSearchResultInterfaceFactory as SearchResultsFactory;

/**
 * Class CustomerGroupPricingRepository
 * @package RLTSquare\CustomerGroupPricing\Model
 */
class CustomerGroupPricingRepository implements CustomerGroupPricingRepositoryInterface
{
    /** @var CustomerGroupPricingFactory */
    private $customerGroupPricingFactory;

    /** @var CustomerGroupPricingResourceModel */
    private $customerGroupPricingResourceModel;

    /** @var CollectionFactory */
    private $collectionFactory;

    /** @var SearchResultsFactory */
    private $searchResultsFactory;

    private $customerSession;

    /**
     * CustomerGroupPricingRepository constructor.
     * @param \RLTSquare\CustomerGroupPricing\Model\CustomerGroupPricingFactory $customerGroupPricingFactory
     * @param CustomerGroupPricingResourceModel $customerGroupPricingResourceModel
     * @param CollectionFactory $collectionFactory
     * @param SearchResultsFactory $searchResultFactory
     */
    public function __construct(
        CustomerGroupPricingFactory $customerGroupPricingFactory,
        CustomerGroupPricingResourceModel $customerGroupPricingResourceModel,
        CollectionFactory $collectionFactory,
        SearchResultsFactory $searchResultFactory,
        SessionFactory $sessionFactory
    )
    {
        $this->customerGroupPricingFactory = $customerGroupPricingFactory;
        $this->customerGroupPricingResourceModel = $customerGroupPricingResourceModel;
        $this->collectionFactory = $collectionFactory;
        $this->searchResultsFactory = $searchResultFactory;
        $this->customerSession = $sessionFactory;
    }

    /**
     * @inheirtDoc
     */
    public function getById($entityId): CustomerGroupPricingInterface
    {
        /** @var CustomerGroupPricing $customerGroupPricing */
        $customerGroupPricing = $this->customerGroupPricingFactory->create();
        $this->customerGroupPricingResourceModel->load($customerGroupPricing, $entityId);
        if (!$customerGroupPricing) {
            throw new NoSuchEntityException(
                __('Unable to find the results against %1 ID', $entityId)
            );
        }
        return $customerGroupPricing;
    }

    /**
     * @inheirtDoc
     */
    public function getByCustomerGroupPricingId($customerGroupPricingId): CustomerGroupPricingInterface
    {
        /** @var CustomerGroupPricing $customerGroupPricing */
        $customerGroupPricing = $this->customerGroupPricingFactory->create();
        $this->customerGroupPricingResourceModel->load(
            $customerGroupPricing,
            $customerGroupPricingId,
            'customer_pricing_id'
        );
        if (!$customerGroupPricing) {
            throw new NoSuchEntityException(
                __('Unable to find the results against %1 ID', $customerGroupPricingId)
            );
        }
        return $customerGroupPricing;
    }

    /**
     * @inheirtDoc
     */
    public function getByProductId($productId): CustomerGroupPricingInterface
    {
        /** @var CustomerGroupPricing $customerGroupPricing */
        $customerGroupPricing = $this->customerGroupPricingFactory->create();
        $this->customerGroupPricingResourceModel->load($customerGroupPricing, $productId, 'product_id');
        if (!$customerGroupPricing) {
            throw new NoSuchEntityException(
                __('Unable to find the results against %1 ID', $productId)
            );
        }
        return $customerGroupPricing;
    }

    /**
     * @inheirtDoc
     */
    public function save(CustomerGroupPricingInterface $customerGroupPricing)
    {
        /** @var CustomerGroupPricingInterface $customerGroupPricingObject */
        $this->customerGroupPricingResourceModel->save($customerGroupPricing);
    }

    /**
     * @inheirtDoc
     */
    public function delete(CustomerGroupPricingInterface $customerGroupPricing): void
    {
        $this->customerGroupPricingResourceModel->delete($customerGroupPricing);
    }

    /**
     * @inheirtDoc
     */
    public function deleteById($entityId): void
    {
        $customGroupPricing = $this->getById($entityId);
        $this->delete($customGroupPricing);
    }

    /**
     * @inheirtDoc
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        /** @var Collection $collection */
        $collection = $this->collectionFactory->create();
        $this->addFiltersToCollection($searchCriteria, $collection);
        $this->addSortOrdersToCollection($searchCriteria, $collection);
        $this->addPagingToCollection($searchCriteria, $collection);

        $collection->load();

        return $this->buildSearchResult($searchCriteria, $collection);
    }

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @param Collection $collection
     */
    private function addFiltersToCollection(
        SearchCriteriaInterface $searchCriteria,
        Collection $collection
    )
    {
        foreach ($searchCriteria->getFilterGroups() as $filterGroup) {
            $fields = $conditions = [];
            foreach ($filterGroup->getFilters() as $filter) {
                $fields[] = $filter->getField();
                $conditions[] = [$filter->getConditionType() => $filter->getValue()];
            }
            $collection->addFieldToFilter($fields, $conditions);
        }
    }

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @param Collection $collection
     */
    private function addSortOrdersToCollection(
        SearchCriteriaInterface $searchCriteria,
        Collection $collection
    ) {
        foreach ((array)$searchCriteria->getSortOrders() as $sortOrder) {
            $direction = $sortOrder->getDirection() == SortOrder::SORT_ASC ? 'asc' : 'desc';
            $collection->addOrder($sortOrder->getField(), $direction);
        }
    }

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @param Collection $collection
     */
    private function addPagingToCollection(SearchCriteriaInterface $searchCriteria, Collection $collection)
    {
        $collection->setPageSize($searchCriteria->getPageSize());
        $collection->setCurPage($searchCriteria->getCurrentPage());
    }

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @param Collection $collection
     * @return \RLTSquare\CustomerGroupPricing\Api\Data\CustomerGroupPricingSearchResultInterface
     */
    private function buildSearchResult(SearchCriteriaInterface $searchCriteria, Collection $collection)
    {
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }

    /**
     * @return int|null
     */
    public function getCustomerId()
    {
        /** @var \Magento\Customer\Model\Session $customerSession */
        $customerSession = $this->customerSession->create();

        $isLoggedIn = $customerSession->isLoggedIn();
        $customerId = $isLoggedIn ? $customerSession->getId() : null;

        return $customerId;
    }
}
