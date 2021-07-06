<?php
/**
 *
 * @category : RLTSquare
 * @Package  : RLTSquare_CustomerGroupPricing
 * @Author   : RLTSquare <support@rltsquare.com>
 * @copyright Copyright 2021 © rltsquare.com All right reserved
 * @license https://rltsquare.com/
 */

namespace RLTSquare\CustomerGroupPricing\Model\ResourceModel\CustomerGroupPricing;

use \RLTSquare\CustomerGroupPricing\Model\ResourceModel\AbstractCollection;
use RLTSquare\CustomerGroupPricing\Model\CustomerGroupPricing;
use RLTSquare\CustomerGroupPricing\Model\ResourceModel\CustomerGroupPricing as CustomerGroupPricingResourceModel;

/**
 * Class Collection
 * @package RLTSquare\CustomerGroupPricing\Model\ResourceModel\CustomerGroupPricing
 */
class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'customer_pricing_id';
    /**
     * Load data for preview flag
     *
     * @var bool
     */
    protected $_previewFlag;

    /**
     * @inheirtDoc
     */
    protected function _construct()
    {
        $this->_init(
            CustomerGroupPricing::class,
            CustomerGroupPricingResourceModel::class
        );

        $this->_map['fields']['customer_pricing_id'] = 'main_table.customer_pricing_id';
    }

    public function addPriorityFilter($dir = 'ASC')
    {
        $this->getSelect()
            ->order('main_table.sort_order ' . $dir);
        return $this;
    }
}
