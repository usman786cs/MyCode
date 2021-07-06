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

/**
 * Class ProductListing
 * @package RLTSquare\CustomerGroupPricing\Model
 */
class ProductListing extends \Magento\Framework\Model\AbstractModel
{
    /**
     * @inheirtDoc
     */
    protected function _construct()
    {
        $this->_init(
            'RLTSquare\CustomerGroupPricing\Model\ResourceModel\ProductListing'
        );
    }

    /**
     * @return string[]
     */
    public function getAvailableStatuses()
    {
        $availableOptions = ['1' => 'Enable',
            '0' => 'Disable'];
        return $availableOptions;
    }
}
