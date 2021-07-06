<?php declare(strict_types=1);
/**
 *
 * @category : RLTSquare
 * @Package  : RLTSquare_FraudProtection
 * @Author   : RLTSquare <support@rltsquare.com>
 * @copyright Copyright 2021 © rltsquare.com All right reserved
 * @license https://rltsquare.com/
 */

namespace RLTSquare\FraudProtection\Model\ValidationRules;

use Magento\Framework\Validation\ValidationResultFactory;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\ValidationRules\QuoteValidationRuleInterface;
use RLTSquare\FraudProtection\Model\FraudCustomer;

class FraudCustomerValidationRule implements QuoteValidationRuleInterface
{
    /**
     * @var FraudCustomer
     */
    private $fraudCustomer;
    /**
     * @var string
     */
    private $generalMessage;
    /**
     * @var ValidationResultFactory
     */
    private $validationResultFactory;

    /**
     * FraudCustomerValidationRule constructor.
     * @param FraudCustomer $fraudCustomer
     * @param ValidationResultFactory $validationResultFactory
     * @param string $generalMessage
     */
    public function __construct(
        FraudCustomer $fraudCustomer,
        ValidationResultFactory $validationResultFactory,
        string $generalMessage = ''
    ) {
        $this->fraudCustomer = $fraudCustomer;
        $this->validationResultFactory = $validationResultFactory;
        $this->generalMessage = $generalMessage;
    }

    /**
     * @param Quote $quote
     * @return array
     */
    public function validate(Quote $quote): array
    {
        $validationErrors = [];
        $email = $quote->getCustomerEmail();
        $ip = $quote->getRemoteIp();
        $validationResult = $this->fraudCustomer->isFraudUser($email, $ip);
        if ($validationResult) {
            $validationErrors = [__($this->generalMessage)];
        }
        return [$this->validationResultFactory->create(['errors' => $validationErrors])];
    }
}
