<?php
/**
 *
 * @category : RLTSquare
 * @Package  : RLTSquare_CustomerGroupPricing
 * @Author   : RLTSquare <support@rltsquare.com>
 * @copyright Copyright 2021 © rltsquare.com All right reserved
 * @license https://rltsquare.com/
 */

namespace RLTSquare\CustomerGroupPricing\Api;

use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Api\SearchCriteriaInterface;
use RLTSquare\CustomerGroupPricing\Api\Data\CustomerGroupPricingInterface;
use RLTSquare\CustomerGroupPricing\Model\ResourceModel\CustomerGroupPricing as CustomerGroupPricingResourceModel;

/**
 * Interface CustomerGroupPricingRepositoryInterface
 * @package RLTSquare\CustomerGroupPricing\Api
 */
interface CustomerGroupPricingRepositoryInterface
{
    /**
     * @param CustomerGroupPricingInterface $customerGroupPricing
     * @throws CouldNotSaveException
     */
    public function save(CustomerGroupPricingInterface $customerGroupPricing);

    /**
     * @param $entityId
     * @return CustomerGroupPricingInterface
     * @throws NoSuchEntityException
     */
    public function getById($entityId): CustomerGroupPricingInterface;

    /**
     * @param $productId
     * @return CustomerGroupPricingInterface
     * @throws NoSuchEntityException
     */
    public function getByProductId($productId): CustomerGroupPricingInterface;

    /**
     * @param $customerGroupPricingId
     * @return CustomerGroupPricingInterface
     * @throws NoSuchEntityException
     */
    public function getByCustomerGroupPricingId($customerGroupPricingId): CustomerGroupPricingInterface;

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return array CustomerGroupPricingInterface
     * @throws LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * @param CustomerGroupPricingInterface $customerGroupPricing
     * @throws CouldNotDeleteException
     */
    public function delete(CustomerGroupPricingInterface $customerGroupPricing): void;

    /**
     * @param $entityId
     * @throws CouldNotDeleteException
     */
    public function deleteById($entityId): void;
}
