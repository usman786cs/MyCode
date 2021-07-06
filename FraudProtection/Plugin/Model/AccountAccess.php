<?php declare(strict_types=1);
/**
 *
 * @category : RLTSquare
 * @Package  : RLTSquare_FraudProtection
 * @Author   : RLTSquare <support@rltsquare.com>
 * @copyright Copyright 2021 © rltsquare.com All right reserved
 * @license https://rltsquare.com/
 */

namespace RLTSquare\FraudProtection\Plugin\Model;

use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Model\AccountManagement;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\State\UserLockedException;
use RLTSquare\FraudProtection\Model\FraudCustomer;

/**
 * Class AccountAccess
 *
 * Class to prevent customers from registration and login.
 */
class AccountAccess
{
    /**
     * @var FraudCustomer
     */
    private $fraudCustomer;

    /**
     * AccountAccess constructor.
     * @param FraudCustomer $fraudCustomer
     */
    public function __construct(
        FraudCustomer $fraudCustomer
    ) {
        $this->fraudCustomer = $fraudCustomer;
    }

    /**
     * @param AccountManagement $subject
     * @param $result
     * @return mixed
     * @throws LocalizedException
     */
    public function afterAuthenticate(AccountManagement $subject, $result)
    {
        if ($this->fraudCustomer->isFraudUser($result->getEmail())) {
            throw new LocalizedException(
                __('Your account is unavailable. Please contact Customer Service.')
            );
        }
        return $result;
    }

    /**
     * Used Around plugin so we can completely avoid the execution of createAccount method if
     * the request is made by fraud customer.
     *
     * @param AccountManagement $subject
     * @param callable $proceed
     * @param CustomerInterface $customer
     * @param null $password
     * @param string $redirectUrl
     * @return mixed
     * @throws UserLockedException
     */
    public function aroundCreateAccount(
        AccountManagement $subject,
        callable $proceed,
        CustomerInterface $customer,
        $password = null,
        $redirectUrl = ''
    ) {
        $fraud = $this->fraudCustomer->isFraudUser($customer->getEmail());
        if ($fraud) {
            throw new UserLockedException(
                __('Your account is unavailable. Please contact Customer Service.')
            );
        }
        $result = $proceed($customer, $password, $redirectUrl);
        return $result;
    }
}
