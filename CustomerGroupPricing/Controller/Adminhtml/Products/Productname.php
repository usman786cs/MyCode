<?php
/**
 *
 * @category : RLTSquare
 * @Package  : RLTSquare_CustomerGroupPricing
 * @Author   : RLTSquare <support@rltsquare.com>
 * @copyright Copyright 2021 © rltsquare.com All right reserved
 * @license https://rltsquare.com/
 */

namespace RLTSquare\CustomerGroupPricing\Controller\Adminhtml\Products;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;

class Productname extends \Magento\Backend\App\Action
{
    /** @var \Magento\Eav\Model\Config */
    protected $eavConfig;

    /** @var \Magento\Framework\Session\SessionManagerInterface */
    protected $_coreSession;

    /** @var ProductRepositoryInterface */
    protected $productRepository;

    /**
     * Productname constructor.
     * @param \Magento\Eav\Model\Config $eavConfig
     * @param \Magento\Framework\Session\SessionManagerInterface $coreSession
     * @param Action\Context $context
     */
    public function __construct(
        \Magento\Eav\Model\Config $eavConfig,
        \Magento\Framework\Session\SessionManagerInterface $coreSession,
        Action\Context $context,
        ProductRepositoryInterface $productRepository
    ) {
        $this->eavConfig = $eavConfig;
        $this->_coreSession = $coreSession;
        $this->productRepository = $productRepository;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     * @throws NoSuchEntityException
     */
    public function execute()
    {
        $data = [];
        $this->_coreSession->start();
        $id = $this->_coreSession->getProductId();
        try {
            $product = $this->productRepository->getById($id);
        } catch (NoSuchEntityException $noSuchEntityException) {
            throw new NoSuchEntityException(
                __('No Product With ID %1 Exists.', $id)
            );
        }
        $data['product_name'] = $product->getName();
        $data['product_sku'] = $product->getSKU();
        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $resultJson->setData($data);
        return $resultJson;
    }
}
