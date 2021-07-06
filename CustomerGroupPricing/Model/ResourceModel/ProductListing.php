<?php
/**
 *
 * @category : RLTSquare
 * @Package  : RLTSquare_CustomerGroupPricing
 * @Author   : RLTSquare <support@rltsquare.com>
 * @copyright Copyright 2021 © rltsquare.com All right reserved
 * @license https://rltsquare.com/
 */

namespace RLTSquare\CustomerGroupPricing\Model\ResourceModel;

/**
 * Class ProductListing
 * @package RLTSquare\CustomerGroupPricing\Model\ResourceModel
 */
class ProductListing extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * @inheirtDoc
     */
    protected function _construct()
    {
        $this->_init('catalog_product_entity', 'entity_id');
    }
}
