<?php
/**
 *
 * @category : RLTSquare
 * @Package  : RLTSquare_CustomerGroupPricing
 * @Author   : RLTSquare <support@rltsquare.com>
 * @copyright Copyright 2021 © rltsquare.com All right reserved
 * @license https://rltsquare.com/
 */

namespace RLTSquare\CustomerGroupPricing\Model\ResourceModel\ProductListing\Grid;

use Magento\Framework\Api\Search\SearchResultInterface;
use Magento\Framework\Search\AggregationInterface;
use RLTSquare\CustomerGroupPricing\Model\ResourceModel\ProductListing\Collection as QuoteCollection;

/**
 * Class Collection
 * @package RLTSquare\CustomerGroupPricing\Model\ResourceModel\ProductListing\Grid
 */
class Collection extends QuoteCollection implements SearchResultInterface
{
    /** @var */
    protected $aggregations;

    /** @var \RLTSquare\CustomerGroupPricing\Model\ResourceModel\CustomerGroupPricing */
    protected $rmCustomerGroupPricing;

    public function __construct(
        \RLTSquare\CustomerGroupPricing\Model\ResourceModel\CustomerGroupPricing $rmCustomerGroupPricing,
        \Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        $mainTable,
        $eventPrefix,
        $eventObject,
        $resourceModel,
        $model = 'Magento\Framework\View\Element\UiComponent\DataProvider\Document',
        $connection = null,

        \Magento\Framework\Model\ResourceModel\Db\AbstractDb $resource = null
    ) {
        $this->rmCustomerGroupPricing = $rmCustomerGroupPricing;
        parent::__construct(
            $entityFactory,
            $logger,
            $fetchStrategy,
            $eventManager,
            $connection,
            $resource
        );
        $this->_eventPrefix = $eventPrefix;
        $this->_eventObject = $eventObject;
        $this->_init($model, $resourceModel);
        $this->setMainTable($mainTable);
    }

    /**
     * @return \Magento\Framework\DB\Select|Collection|void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _initSelect()
    {
        $prodids = $this->rmCustomerGroupPricing->getDataCollection();
        if (!empty($prodids)) {
            $select = $this->getSelect()->from(
                ['main_table' => $this->getResource()->getMainTable()]
            );
            foreach ($prodids as $key => $prodid) {
                $select->where('entity_id != ' . $prodid["product_id"] . '');
            }
            return $select;
        } else {
            $this->getSelect()->from(
                ['main_table' => $this->getResource()->getMainTable()]
            );
        }
    }

    /**
     * @return \Magento\Framework\Api\Search\AggregationInterface
     */
    public function getAggregations()
    {
        return $this->aggregations;
    }

    /**
     * @param \Magento\Framework\Api\Search\AggregationInterface $aggregations
     * @return Collection|void
     */
    public function setAggregations($aggregations)
    {
        $this->aggregations = $aggregations;
    }

    /**
     * @param null $limit
     * @param null $offset
     * @return array
     */
    public function getAllIds($limit = null, $offset = null)
    {
        return $this->getConnection()->fetchCol($this->_getAllIdsSelect($limit, $offset), $this->_bindParams);
    }

    /**
     * @return null
     */
    public function getSearchCriteria()
    {
        return null;
    }

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface|null $searchCriteria
     * @return $this|Collection
     */
    public function setSearchCriteria(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria = null)
    {
        return $this;
    }

    /**
     * @return int
     */
    public function getTotalCount()
    {
        return $this->getSize();
    }

    /**
     * @param int $totalCount
     * @return $this|Collection
     */
    public function setTotalCount($totalCount)
    {
        return $this;
    }

    /**
     * @param array|null $items
     * @return $this|Collection
     */
    public function setItems(array $items = null)
    {
        return $this;
    }
}
