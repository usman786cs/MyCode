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
 * Class CustomerGroupPricing
 * @package RLTSquare\CustomerGroupPricing\Model\ResourceModel
 */
class CustomerGroupPricing extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * @inheirtDoc
     */
    protected function _construct()
    {
        $this->_init(
            'rlts_customer_group_pricing',
            'customer_pricing_id'
        );
    }

    /**
     * @param $productid
     * @return array
     */
    public function getDataUsingProductId($productid)
    {
        $select = $this->getConnection()
            ->select()
            ->from($this->getTable('rlts_customer_group_pricing'))
            ->where('product_id = ?', $productid);
        return $this->getConnection()->fetchAll($select);
    }

    /**
     * @param $data
     */
    public function saveData($data)
    {
        unset($data['form_key']);
        $this->getConnection()->insert($this->getTable('rlts_customer_group_pricing'), $data);
    }

    /**
     * @param $data
     */
    public function updateData($data)
    {
        if (!empty($data['customerpricing_dynamicrows'])) {
            $this->getConnection()->update(
                $this->getTable('rlts_customer_group_pricing'),
                ['customerpricing_dynamicrows' => $data['customerpricing_dynamicrows']],
                ['customer_pricing_id = ?' => (int)$data['customer_pricing_id']]
            );
        } else {
            $this->getConnection()->update(
                $this->getTable('rlts_customer_group_pricing'),
                ['customerpricing_dynamicrows' => ""],
                ['customer_pricing_id = ?' => (int)$data['customer_pricing_id']]
            );
        }
    }

    /**
     * @return array
     */
    public function getDataCollection()
    {
        $select = $this->getConnection()
            ->select()
            ->from($this->getTable('rlts_customer_group_pricing'), ['product_id']);
        return $this->getConnection()->fetchAll($select);
    }
}
