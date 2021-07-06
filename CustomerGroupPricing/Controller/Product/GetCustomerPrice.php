<?php
/**
 *
 * @category : RLTSquare
 * @Package  : RLTSquare_CustomerGroupPricing
 * @Author   : RLTSquare <support@rltsquare.com>
 * @copyright Copyright 2021 © rltsquare.com All right reserved
 * @license https://rltsquare.com/
 */

namespace RLTSquare\CustomerGroupPricing\Controller\Product;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Catalog\Api\ProductRepositoryInterface;
use RLTSquare\CustomerGroupPricing\Model\CustomerGroupPricingRepository;

/**
 * Class GetCustomerPrice
 * @package RLTSquare\CustomerGroupPricing\Controller\Product
 */
class GetCustomerPrice implements HttpPostActionInterface
{
    /** @var JsonFactory */
    private $jsonFactory;

    /** @var RequestInterface */
    private $request;

    /** @var Json */
    private $json;

    /** @var CustomerGroupPricingRepository */
    private $customerGroupPricingRepository;

    /** @var \Magento\Framework\Stdlib\DateTime\DateTime */
    private $dateTime;

    /** @var \Magento\Customer\Api\CustomerRepositoryInterface */
    private $customerRepositoryInterface;

    /** @var ProductRepositoryInterface */
    private $productRepository;

    /**
     * GetCustomerPrice constructor.
     * @param JsonFactory $jsonFactory
     * @param RequestInterface $request
     * @param Json $json
     * @param CustomerGroupPricingRepository $customerGroupPricingRepository
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $dateTime
     * @param \Magento\Customer\Api\CustomerRepositoryInterface $customerRepositoryInterface
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        JsonFactory $jsonFactory,
        RequestInterface $request,
        Json $json,
        CustomerGroupPricingRepository $customerGroupPricingRepository,
        \Magento\Framework\Stdlib\DateTime\DateTime $dateTime,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepositoryInterface,
        ProductRepositoryInterface $productRepository
    )
    {
        $this->jsonFactory = $jsonFactory;
        $this->request = $request;
        $this->json = $json;
        $this->customerGroupPricingRepository = $customerGroupPricingRepository;
        $this->dateTime = $dateTime;
        $this->customerRepositoryInterface = $customerRepositoryInterface;
        $this->productRepository = $productRepository;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Json|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $resultJson = $this->jsonFactory->create();
        $productId = $this->request->getPostValue("product_id");
        if (!$this->request->isPost() || !$productId) {
            return $resultJson->setData(
                ["status" => __('error'), "msg" => __('Invalid Request Format OR Product Id.')]
            );
        }

        try {
            $currentProduct = $this->productRepository->getById($productId);
        } catch (NoSuchEntityException $noSuchEntityException) {
            return $resultJson->setData(
                ["status" => __('error'), "msg" => __('Something went wrong while calculating the price')]
            );
        }
        try {
            $productData = $this->customerGroupPricingRepository->getByProductId($productId);
        } catch (NoSuchEntityException $noSuchEntityException) {
            return $resultJson->setData(
                ["product_id" => $currentProduct->getId(), "product_price" => $currentProduct->getPrice()]
            );
        }

        if ($productData->getEntityId() === null) {
            return $resultJson->setData(
                ["product_id" => $currentProduct->getId(), "product_price" => $currentProduct->getPrice()]
            );
        }
        $rules = $productData->getCustomerPricingDynamicRows();
        $rules = $this->json->unserialize($rules);
        foreach ($rules as $rule) {
            if (!$rule['status']) {
                return $resultJson->setData(
                    ["product_id" => $currentProduct->getId(), "product_price" => $currentProduct->getPrice()]
                );
            }

            $date = $this->dateTime->gmtDate();
            $currentTime = strtotime($date);
            $startTime = strtotime($rule['start_date']);
            $endTime = strtotime($rule['end_date']);
            $customerId = $this->customerGroupPricingRepository->getCustomerId();
            if ($currentTime >= $startTime && $currentTime <= $endTime) {
                if ($rule['apply_price_on'] && $customerId && $customerId == $rule['customer_id']) {
                    return $resultJson->setData(["product_id" => $productId, "product_price" => $rule['price']]);
                } else {
                    $customerGroupId = 0;
                    if ($customerId) {
                        try {
                            /** @var \Magento\Customer\Model\Customer $customer */
                            $customer = $this->customerRepositoryInterface->getById($customerId);
                            $customerGroupId = $customer->getGroupId();
                        } catch (\Exception $exception) {
                            return $resultJson->setData(
                                ["product_id" => $currentProduct->getId(), "product_price" => $currentProduct->getPrice()]
                            );
                        }
                    }
                    if (in_array($customerGroupId, $rule['customer_group'])) {
                        return $resultJson->setData(
                            ["product_id" => $productId, "product_price" => $rule['price']]
                        );
                    }
                }
            }
        }
        return $resultJson->setData(
            ["product_id" => $currentProduct->getId(), "product_price" => $currentProduct->getPrice()]
        );
    }
}
