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

use RLTSquare\CustomerGroupPricing\Api\Data\CustomerGroupPricingInterface;
use RLTSquare\CustomerGroupPricing\Model\ResourceModel\CustomerGroupPricing as CustomerGroupPricingResourceModel;

/**
 * Class CustomerGroupPricing
 * @package RLTSquare\CustomerGroupPricing\Model
 */
class CustomerGroupPricing extends \Magento\Framework\Model\AbstractModel implements CustomerGroupPricingInterface
{
    /**
     * @inheirtDoc
     */
    protected function _construct()
    {
        $this->_init(CustomerGroupPricingResourceModel::class);
    }

    /**
     * @inheirtDoc
     */
    public function getEntityId()
    {
        return $this->getData(self::ENTITY_ID);
    }

    /**
     * @inheirtDoc
     */
    public function setEntityId($entityId): CustomerGroupPricingInterface
    {
        return $this->setData(self::ENTITY_ID, $entityId);
    }

    /**
     * @inheirtDoc
     */
    public function getCustomerId()
    {
        return $this->getData(self::CUSTOMER_ID);
    }

    /**
     * @inheirtDoc
     */
    public function setCustomerId($customerId): CustomerGroupPricingInterface
    {
        return $this->setData(self::CUSTOMER_ID, $customerId);
    }

    /**
     * @inheirtDoc
     */
    public function getProductId(): int
    {
        return $this->getData(self::PRODUCT_ID);
    }

    /**
     * @inheirtDoc
     */
    public function setProductId($productId): CustomerGroupPricingInterface
    {
        return $this->setData(self::PRODUCT_ID, $productId);
    }

    /**
     * @inheirtDoc
     */
    public function getProductSKU(): string
    {
        return $this->getData(self::PRODUCT_SKU);
    }

    /**
     * @inheirtDoc
     */
    public function setProductSKU($productSKU): CustomerGroupPricingInterface
    {
        return $this->setData(self::PRODUCT_SKU, $productSKU);
    }

    /**
     * @inheirtDoc
     */
    public function getProductName(): string
    {
        return $this->getData(self::PRODUCT_NAME);
    }

    /**
     * @inheirtDoc
     */
    public function setProductName($productName): CustomerGroupPricingInterface
    {
        return $this->setData(self::PRODUCT_NAME, $productName);
    }

    /**
     * @inheirtDoc
     */
    public function getCustomerPricingDynamicRows(): string
    {
        return $this->getData(self::CUSTOMERPRICING_DYNAMICROWS);
    }

    /**
     * @inheirtDoc
     */
    public function setCustomerPricingDynamicRows($customerPricingDynamicRows): CustomerGroupPricingInterface
    {
        return $this->setData(self::CUSTOMERPRICING_DYNAMICROWS, $customerPricingDynamicRows);
    }

    /**
     * @inheirtDoc
     */
    public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * @inheirtDoc
     */
    public function setCreatedAt($createdAt): CustomerGroupPricingInterface
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    /**
     * @inheirtDoc
     */
    public function setUpdatedAt($updatedAt): CustomerGroupPricingInterface
    {
        return $this->setData(self::UPDATE_AT, $updatedAt);
    }

    /**
     * @inheirtDoc
     */
    public function getUpdatedAt()
    {
        return $this->getData(self::UPDATE_AT);
    }
}
