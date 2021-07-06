<?php
/**
 *
 * @category : RLTSquare
 * @Package  : RLTSquare_CustomerGroupPricing
 * @Author   : RLTSquare <support@rltsquare.com>
 * @copyright Copyright 2021 © rltsquare.com All right reserved
 * @license https://rltsquare.com/
 */

namespace RLTSquare\CustomerGroupPricing\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Customer\Model\ResourceModel\Group\Collection as GroupCollection;
use Magento\Customer\Model\ResourceModel\Group\CollectionFactory;

/**
 * Class CustomerGroups
 * @package RLTSquare\CustomerGroupPricing\Model\Config\Source
 */
class CustomerGroups implements OptionSourceInterface
{
    /** @var CollectionFactory */
    protected $customerGroup;

    /**
     * CustomerGroups constructor.
     * @param CollectionFactory $customerGroup
     */
    public function __construct(
        CollectionFactory $customerGroup
    ) {
        $this->customerGroup = $customerGroup;
    }

    /**
     * @return array
     */
    public function toOptionArray(): array
    {
        return $this->getCustomerGroups();
    }

    /**
     * @return array
     */
    public function getCustomerGroups(): array
    {
        /** @var GroupCollection $customerGroups */
        $customerGroups = $this->customerGroup->create();
        $customerGroups = $customerGroups->toOptionArray();

        $groups_array = [];
        foreach ($customerGroups as $customerGroup) {
            $groups_array[] = [
                'value' => $customerGroup['value'],
                'label' => $customerGroup['label']
            ];
        }
        return $groups_array;
    }
}
