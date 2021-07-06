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

use Magento\Backend\App\Action\Context;
use Magento\Backend\App\Action;
use Magento\Framework\Exception\NoSuchEntityException;
use RLTSquare\CustomerGroupPricing\Model\CustomerGroupPricing;
use RLTSquare\CustomerGroupPricing\Model\CustomerGroupPricingFactory;
use RLTSquare\CustomerGroupPricing\Model\CustomerGroupPricingRepository;
use Magento\Framework\Exception\LocalizedException;
use Magento\TestFramework\Inspection\Exception;
use Magento\Framework\Serialize\Serializer\Json;

/**
 * Class Save
 * @package RLTSquare\CustomerGroupPricing\Controller\Adminhtml\Products
 */
class Save extends \Magento\Backend\App\Action
{
    /** @var \Magento\Framework\App\Config\ScopeConfigInterface */
    protected $scopeConfig;

    /** @var \Magento\Framework\Escaper */
    protected $_escaper;

    /** @var \Magento\Framework\Session\SessionManagerInterface */
    protected $_coreSession;

    /** @var \Magento\Catalog\Api\ProductRepositoryInterface */
    protected $productRepositoryInterface;

    /** @var CustomerGroupPricing */
    protected $customerGroupPricingModel;

    /** @var CustomerGroupPricingRepository */
    protected $customerGroupPricingRepository;

    /** @var Json */
    protected $json;

    /** @var CustomerGroupPricingFactory */
    protected $customerGroupPricingFactory;

    /**
     * Save constructor.
     * @param Context $context
     * @param \Magento\Framework\Escaper $escaper
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepositoryInterface
     * @param CustomerGroupPricing $customerGroupPricingModel
     * @param CustomerGroupPricingRepository $customerGroupPricingRepository
     * @param \Magento\Framework\Session\SessionManagerInterface $coreSession
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param CustomerGroupPricingFactory $customerGroupPricingFactory
     * @param Json $json
     */
    public function __construct(
        Context $context,
        \Magento\Framework\Escaper $escaper,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepositoryInterface,
        \RLTSquare\CustomerGroupPricing\Model\CustomerGroupPricing $customerGroupPricingModel,
        CustomerGroupPricingRepository $customerGroupPricingRepository,
        \Magento\Framework\Session\SessionManagerInterface $coreSession,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        CustomerGroupPricingFactory $customerGroupPricingFactory,
        Json $json
    ) {
        $this->customerGroupPricingModel = $customerGroupPricingModel;
        $this->productRepositoryInterface = $productRepositoryInterface;
        $this->scopeConfig = $scopeConfig;
        $this->_coreSession = $coreSession;
        $this->_escaper = $escaper;
        $this->customerGroupPricingRepository = $customerGroupPricingRepository;
        $this->json = $json;
        $this->customerGroupPricingFactory = $customerGroupPricingFactory;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Redirect|\Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     * @throws NoSuchEntityException
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        $this->_coreSession->start();
        $id = $this->_coreSession->getProductId();
        if ($id && empty($data['customer_pricing_id'])) {
            $data['product_id'] = $id;
            $this->_coreSession->start();
            $this->_coreSession->unsProductId();
        }
        if ($data) {
            $id = $this->getRequest()->getParam('customer_pricing_id');
            if (!empty($data['customerpricing_dynamicrows'])) {
                $data['customerpricing_dynamicrows'] = $this->json->serialize($data['customerpricing_dynamicrows']);

                /** @var \Magento\Catalog\Model\Product $product */
                $product = $this->productRepositoryInterface->getById($data['product_id']);
                $data['product_name'] = $product->getName();
                $data['product_sku'] = $product->getSKU();
            }

            try {
                $model = $this->customerGroupPricingRepository->getByCustomerGroupPricingId($id);
            } catch (NoSuchEntityException $noSuchEntityException) {
                $this->messageManager->addErrorMessage(__('This Rule no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }
            if (!$model->getEntityId() && $id) {
                $this->messageManager->addErrorMessage(__('This Rule no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }
            try {
                if (!$data['customer_pricing_id']) {
                    unset($data['customer_pricing_id']);
                }
                /** @var CustomerGroupPricing $customerGroupPricing */
                $customerGroupPricing = $this->customerGroupPricingFactory->create();
                $customerGroupPricing->setData($data);
                $this->customerGroupPricingRepository->save($customerGroupPricing);
                $this->messageManager->addSuccessMessage(__('Rule Saved successfully'));
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['customer_pricing_id' => $model->getId()]);
                }
                return $resultRedirect->setPath('*/index/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the Rule.'));
            }
            return $resultRedirect->setPath(
                '*/*/edit',
                [
                    'customer_pricing_id' => $this->getRequest()->getParam('customer_pricing_id')
                ]
            );
        }
        return $resultRedirect->setPath('*/*/');
    }
}
