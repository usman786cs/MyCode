<?php
/**
 *
 * @category : RLTSquare
 * @Package  : RLTSquare_CustomerGroupPricing
 * @Author   : RLTSquare <support@rltsquare.com>
 * @copyright Copyright 2021 © rltsquare.com All right reserved
 * @license https://rltsquare.com/
 */

namespace RLTSquare\CustomerGroupPricing\Plugin;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Serialize\Serializer\Json;
use RLTSquare\CustomerGroupPricing\Model\CustomerGroupPricingRepository;

/**
 * Class ChangePrice
 * @package RLTSquare\CustomerGroupPricing\Plugin
 */
class ChangePrice
{
    /** @var \Magento\Framework\Stdlib\DateTime\DateTime */
    protected $dateTime;

    /** @var \Magento\Customer\Api\CustomerRepositoryInterface */
    protected $customerRepositoryInterface;

    /** @var CustomerGroupPricingRepository */
    protected $customerGroupPricingRepository;

    /** @var Json */
    protected $json;

    /**
     * ChangePrice constructor.
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $dateTime
     * @param \Magento\Customer\Api\CustomerRepositoryInterface $customerRepositoryInterface
     * @param CustomerGroupPricingRepository $customerGroupPricingRepository
     * @param Json $json
     */
    public function __construct(
        \Magento\Framework\Stdlib\DateTime\DateTime $dateTime,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepositoryInterface,
        CustomerGroupPricingRepository $customerGroupPricingRepository,
        Json $json
    ) {
        $this->dateTime = $dateTime;
        $this->customerRepositoryInterface = $customerRepositoryInterface;
        $this->customerGroupPricingRepository = $customerGroupPricingRepository;
        $this->json = $json;
    }

    /**
     * @param \Magento\Catalog\Model\Product $subject
     * @param $result
     * @return mixed
     */
    public function afterGetPrice(
        \Magento\Catalog\Model\Product $subject,
        $result
    ) {
        try {
            $productData = $this->customerGroupPricingRepository->getByProductId($subject->getEntityId());
        } catch (NoSuchEntityException $noSuchEntityException) {
            return $result;
        }

        if ($productData->getEntityId() === null) {
            return $result;
        }

        $rules = $productData->getCustomerPricingDynamicRows();
        $rules = $this->json->unserialize($rules);
        foreach ($rules as $rule) {
            if (!$rule['status']) {
                return $result;
            }
            $date = $this->dateTime->gmtDate();
            $currentTime = strtotime($date);
            $startTime = strtotime($rule['start_date']);
            $endTime = strtotime($rule['end_date']);
            $customerId = $this->customerGroupPricingRepository->getCustomerId();
            if ($currentTime >= $startTime && $currentTime <= $endTime) {
                if ($rule['apply_price_on'] && $customerId && $customerId == $rule['customer_id']) {
                    return $rule['price'];
                } else {
                    $customerGroupId = 0;
                    if ($customerId) {
                        try {
                            /** @var \Magento\Customer\Model\Customer $customer */
                            $customer = $this->customerRepositoryInterface->getById($customerId);
                            $customerGroupId = $customer->getGroupId();
                        } catch (\Exception $exception) {
                            return $result;
                        }
                    }
                    if (in_array($customerGroupId, $rule['customer_group'])) {
                        return $rule['price'];
                    }
                }
            }
        }
        return $result;
    }
}
