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
use RLTSquare\CustomerGroupPricing\Model\CustomerGroupPricingRepository;
use Magento\Framework\App\Request\DataPersistorInterface;

class Edit extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /** @var CustomerGroupPricingRepository */
    protected $customerGroupRepository;

    /** @var DataPersistorInterface */
    private $dataPersister;

    /**
     * Edit constructor.
     * @param Action\Context $context
     * @param CustomerGroupPricingRepository $customerGroupPricingRepository
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param DataPersistorInterface $dataPersistor
     */
    public function __construct(
        Action\Context $context,
        CustomerGroupPricingRepository $customerGroupPricingRepository,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        DataPersistorInterface $dataPersistor
    )
    {
        $this->resultPageFactory = $resultPageFactory;
        $this->customerGroupRepository = $customerGroupPricingRepository;
        $this->dataPersister = $dataPersistor;
        parent::__construct($context);
    }

    /**
     * @ingeritdoc
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('RLTSquare_CustomerGroupPricing::customergrouppricing');
    }

    /**
     * Init actions
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    protected function _initAction()
    {
        // load layout, set active menu and breadcrumbs
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('RLTSquare_CustomerGroupPricing::customergrouppricing')
            ->addBreadcrumb(__('Customer Group Pricing'), __('Customer Group Pricing'))
            ->addBreadcrumb(__('Manage Customer Group Pricing'), __('Manage Customer Group Pricing'));
        return $resultPage;
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Page|\Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Redirect|\Magento\Framework\Controller\ResultInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute()
    {

        $id = $this->getRequest()->getParam('customer_pricing_id');
        $model = null;
        if ($id) {
            $model = $this->customerGroupRepository->getByCustomerGroupPricingId($id);
            if (!$model->getEntityId()) {
                $this->messageManager->addErrorMessage(__('This record no longer exists.'));
                /** \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }

        $this->dataPersister->clear('customergrouppricing');
        $this->dataPersister->set('customergrouppricing', $model);

        //Build edit form
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->_initAction();

        $resultPage->addBreadcrumb(
            $id ? __('Edit Customer Group Pricing') : __('New Customer Group Pricing'),
            $id ? __('Edit Customer Group Pricing') : __('New Customer Group Pricing')
        );

        $resultPage->getConfig()->getTitle()->prepend(__('Customer Group Pricing'));
        $resultPage->getConfig()->getTitle()
            ->prepend(
                $model !== null ? __('Edit "%1"', $model->getProductName()) : __('New Customer Group Pricing')
            );
        return $resultPage;
    }
}
