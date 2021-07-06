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

/**
 * Class ApplyPriceOn
 * @package RLTSquare\CustomerGroupPricing\Model\Config\Source
 */
class ApplyPriceOn implements OptionSourceInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        $availableOptions = ['0' => 'Customer Group', '1' => 'Selected Customer'];
        $options = [];
        foreach ($availableOptions as $key => $label) {
            $options[] = [
                'label' => $label,
                'value' => $key,
            ];
        }
        return $options;
    }
}
