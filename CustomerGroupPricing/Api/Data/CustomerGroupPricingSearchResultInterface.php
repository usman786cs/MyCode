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

use Magento\Framework\Api\SearchResultsInterface;
use RLTSquare\CustomerGroupPricing\Api\Data\CustomerGroupPricingInterface;

/**
 * Interface CustomerGroupPricingSearchResultInterface
 * @package RLTSquare\CustomerGroupPricing\Api\Data
 */
interface CustomerGroupPricingSearchResultInterface extends SearchResultsInterface
{
    /**
     * @return \RLTSquare\CustomerGroupPricing\Api\Data\CustomerGroupPricingInterface[]
     */
    public function getItems();

    /**
     * @param array $items
     * @return CustomerGroupPricingInterface[]
     */
    public function setItems(array $items);
}
