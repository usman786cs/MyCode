<?php declare(strict_types=1);
/**
 *
 * @category : RLTSquare
 * @Package  : RLTSquare_FraudProtection
 * @Author   : RLTSquare <support@rltsquare.com>
 * @copyright Copyright 2021 © rltsquare.com All right reserved
 * @license https://rltsquare.com/
 */

use Magento\Framework\Component\ComponentRegistrar;

ComponentRegistrar::register(
    ComponentRegistrar::MODULE,
    'RLTSquare_FraudProtection',
    __DIR__
);
