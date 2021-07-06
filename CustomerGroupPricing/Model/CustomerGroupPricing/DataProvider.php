<?php
/**
 *
 * @category : RLTSquare
 * @Package  : RLTSquare_CustomerGroupPricing
 * @Author   : RLTSquare <support@rltsquare.com>
 * @copyright Copyright 2021 © rltsquare.com All right reserved
 * @license https://rltsquare.com/
 */

namespace RLTSquare\CustomerGroupPricing\Model\CustomerGroupPricing;

use RLTSquare\CustomerGroupPricing\Model\ResourceModel\CustomerGroupPricing\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Serialize\Serializer\Json;

/**
 * Class DataProvider
 */
class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /** @var \RLTSquare\CustomerGroupPricing\Model\ResourceModel\CustomerGroupPricing\Collection */
    protected $collection;

    /** @var DataPersistorInterface */
    protected $dataPersistor;

    /** @var \Magento\Framework\Session\SessionManagerInterface */
    protected $_coreSession;

    /** @var */
    protected $loadedData;

    /** @var \Magento\Framework\App\Request\Http */
    protected $request;

    /** @var Json */
    private $json;

    /**
     * Constructor
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $blockCollectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        \Magento\Framework\App\Request\Http $request,
        \Magento\Framework\Session\SessionManagerInterface $coreSession,
        CollectionFactory $blockCollectionFactory,
        DataPersistorInterface $dataPersistor,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        Json $json,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $blockCollectionFactory->create();
        $this->request = $request;
        $this->_coreSession = $coreSession;
        $this->dataPersistor = $dataPersistor;
        $this->json = $json;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();
        if ($this->request->getParam('entity_id')) {
            $this->_coreSession->start();
            $this->_coreSession->setProductId($this->request->getParam('entity_id'));
        }
        foreach ($items as $block) {
            $data = $block->getData();
            $data['customerpricing_dynamicrows'] = $this->json->unserialize($data['customerpricing_dynamicrows']);
            $this->loadedData[$block->getId()] = $data;
        }
        /** @var \Magento\Cms\Model\Block $block */
        $data = $this->dataPersistor->get('rlts_customer_group_pricing');
        if (!empty($data)) {
            $block = $this->collection->getNewEmptyItem();
            $block->setData($data);
            $this->loadedData[$block->getId()] = $block->getData();
            $this->dataPersistor->clear('rlts_customer_group_pricing');
        }
        if (empty($this->loadedData)) {
            return $this->loadedData;
        } else {
            return $this->loadedData;
        }
    }
}
