<?php
/**
 *
 * @category : RLTSquare
 * @Package  : RLTSquare_CustomerGroupPricing
 * @Author   : RLTSquare <support@rltsquare.com>
 * @copyright Copyright 2021 © rltsquare.com All right reserved
 * @license https://rltsquare.com/
 */

namespace RLTSquare\CustomerGroupPricing\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;

/**
 * Class Index
 * @package RLTSquare\CustomerGroupPricing\Controller\Adminhtml\Index
 */
class Index extends \Magento\Backend\App\Action
{
    /** @var \Magento\Framework\View\Result\PageFactory */
    protected $resultPageFactory;

    /**
     * Index constructor.
     * @param Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $resultPage = $this->_initAction();
        $resultPage->getConfig()->getTitle()->prepend(__('Customer Group Pricing'));
        return $resultPage;
    }

    /**
     * @return \Magento\Framework\View\Result\Page
     */
    protected function _initAction()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('RLTSquare_CustomerGroupPricing::customergrouppricing');
        $resultPage->addBreadcrumb(__('Customer Group Pricing'), __('Customer Group Pricing'));
        return $resultPage;
    }
}
