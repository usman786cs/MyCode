<?php
/**
 *
 * @category : RLTSquare
 * @Package  : RLTSquare_CustomerGroupPricing
 * @Author   : RLTSquare <support@rltsquare.com>
 * @copyright Copyright 2021 © rltsquare.com All right reserved
 * @license https://rltsquare.com/
 */

namespace RLTSquare\CustomerGroupPricing\Api\Data;

/**
 * Interface CustomerGroupPricingInterface
 * @package RLTSquare\CustomerGroupPricing\Api\Data
 */
interface CustomerGroupPricingInterface
{
    const ENTITY_ID = 'customer_pricing_id';
    const PRODUCT_ID = 'product_id';
    const PRODUCT_SKU = 'product_sku';
    const PRODUCT_NAME = 'product_name';
    const CUSTOMER_ID = 'customer_id';
    const CUSTOMERPRICING_DYNAMICROWS = 'customerpricing_dynamicrows';
    const CREATED_AT = 'created_at';
    const UPDATE_AT = 'updated_at';

    /**
     * @return mixed
     */
    public function getEntityId();

    /**
     * @param $entityId
     * @return $this
     */
    public function setEntityId($entityId): CustomerGroupPricingInterface;

    /**
     * @return int
     */
    public function getProductId(): int;

    /**
     * @param $productId
     * @return $this
     */
    public function setProductId($productId): CustomerGroupPricingInterface;

    /**
     * @return string
     */
    public function getProductName(): string;

    /**
     * @param $productName
     * @return $this
     */
    public function setProductName($productName): CustomerGroupPricingInterface;

    /**
     * @return string
     */
    public function getProductSKU(): string;

    /**
     * @param $productSKU
     * @return $this
     */
    public function setProductSKU($productSKU): CustomerGroupPricingInterface;

    /**
     * @return mixed
     */
    public function getCustomerId();

    /**
     * @param $customerId
     * @return $this
     */
    public function setCustomerId($customerId): CustomerGroupPricingInterface;

    /**
     * @return string
     */
    public function getCustomerPricingDynamicRows(): string;

    /**
     * @param $customerPricingDynamicRows
     * @return $this
     */
    public function setCustomerPricingDynamicRows($customerPricingDynamicRows): CustomerGroupPricingInterface;

    /**
     * @return mixed
     */
    public function getCreatedAt();

    /**
     * @param $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt): CustomerGroupPricingInterface;

    /**
     * @return mixed
     */
    public function getUpdatedAt();

    /**
     * @param $updatedAt
     * @return $this
     */
    public function setUpdatedAt($updatedAt): CustomerGroupPricingInterface;
}
