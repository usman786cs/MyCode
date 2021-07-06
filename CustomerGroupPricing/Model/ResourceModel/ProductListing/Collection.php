<?php
/**
 *
 * @category : RLTSquare
 * @Package  : RLTSquare_CustomerGroupPricing
 * @Author   : RLTSquare <support@rltsquare.com>
 * @copyright Copyright 2021 © rltsquare.com All right reserved
 * @license https://rltsquare.com/
 */

namespace RLTSquare\CustomerGroupPricing\Model\ResourceModel\ProductListing;

use \RLTSquare\CustomerGroupPricing\Model\ResourceModel\AbstractCollection;

/**
 * Class Collection
 * @package RLTSquare\CustomerGroupPricing\Model\ResourceModel\ProductListing
 */
class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'entity_id';

    /**
     * @inheridoc
     */
    protected function _construct()
    {
        $this->_init('RLTSquare\CustomerGroupPricing\Model\ProductListing', 'RLTSquare\CustomerGroupPricing\Model\ResourceModel\ProductListing');
        $this->_map['fields']['entity_id'] = 'main_table.entity_id';
    }

    /**
     * @param string $dir
     * @return $this
     */
    public function addPriorityFilter($dir = 'ASC')
    {
        $this->getSelect()
            ->order('main_table.sort_order ' . $dir);
        return $this;
    }
}
