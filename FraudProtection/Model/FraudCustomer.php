<?php declare(strict_types=1);
/**
 *
 * @category : RLTSquare
 * @Package  : RLTSquare_FraudProtection
 * @Author   : RLTSquare <support@rltsquare.com>
 * @copyright Copyright 2021 © rltsquare.com All right reserved
 * @license https://rltsquare.com/
 */

namespace RLTSquare\FraudProtection\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class FraudCustomer
 */
class FraudCustomer
{
    /**
     * Configuration paths for enable/disable status of fraud prevention
     */
    const CONFIG_PATH_FRAUD_PREVENTION_STATUS = 'rltsquare_fraud_prevention/general/enable';
    /**
     * Configuration paths for comma seperated list of fraud ips
     */
    const CONFIG_PATH_FRAUD_IPS = 'rltsquare_fraud_prevention/general/forbidden_ips';
    /**
     * Configuration paths for comma separated list of fraud email addresses
     */
    const CONFIG_PATH_FRAUD_EMAILS = 'rltsquare_fraud_prevention/general/forbidden_email';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var RemoteAddress
     */
    private $remoteAddress;

    /**
     * FraudCustomer constructor.
     * @param ScopeConfigInterface $scopeConfig
     * @param StoreManagerInterface $storeManager
     * @param RemoteAddress $remoteAddress
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager,
        RemoteAddress $remoteAddress
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->remoteAddress = $remoteAddress;
    }

    /**
     * @param string $email
     * @param string $ip
     * @return bool
     */
    public function isFraudUser(string $email, string $ip = '')
    {
        if (!$this->isFraudPreventionActive()) {
            return false;
        }

        if (!$ip) {
            $ip = $this->remoteAddress->getRemoteAddress();
        }
        list($fraudEmails, $fraudIps) = $this->getFraudIpAndEmail();
        return in_array(trim($email), $fraudEmails) || in_array(trim($ip), $fraudIps);
    }

    /**
     * @return bool
     */
    private function isFraudPreventionActive()
    {
        try {
            $storeId = $this->storeManager->getStore()->getId();
            return $this->scopeConfig->getValue(
                self::CONFIG_PATH_FRAUD_PREVENTION_STATUS,
                ScopeInterface::SCOPE_STORE,
                $storeId
            );
        } catch (NoSuchEntityException $e) {
            return false;
        }
    }

    /**
     * Returns array of configured Fraud Ips and Email addresses
     * @return array
     */
    private function getFraudIpAndEmail()
    {
        try {
            $websiteId = $this->storeManager->getWebsite()->getId();

            $fraudEmails = $this->getConfigData(self::CONFIG_PATH_FRAUD_EMAILS, $websiteId);
            $fraudIps = $this->getConfigData(self::CONFIG_PATH_FRAUD_IPS, $websiteId);

            return [$fraudEmails, $fraudIps];
        } catch (LocalizedException $e) {
            return [];
        }
    }

    /**
     * @param string $path
     * @param $websiteId
     * @return array
     */
    private function getConfigData(string $path, $websiteId)
    {
        $configString = $this->scopeConfig->getValue(
            $path,
            ScopeInterface::SCOPE_WEBSITE,
            $websiteId
        ) ?: '';
        return array_map('trim', explode(',', $configString));
    }
}
