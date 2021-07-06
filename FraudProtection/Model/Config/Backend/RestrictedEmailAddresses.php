<?php declare(strict_types=1);
/**
 *
 * @category : RLTSquare
 * @Package  : RLTSquare_FraudProtection
 * @Author   : RLTSquare <support@rltsquare.com>
 * @copyright Copyright 2021 © rltsquare.com All right reserved
 * @license https://rltsquare.com/
 */

namespace RLTSquare\FraudProtection\Model\Config\Backend;

use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Value;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Escaper;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;

/**
 * Class RestrictedEmailAddresses
 *
 * Class to validate config value for comma separated email addresses
 */
class RestrictedEmailAddresses extends Value
{
    /**
     * @var Escaper
     */
    private $escaper;
    /**
     * @var ManagerInterface
     */
    private $messageManager;

    /**
     * RestrictedEmailAddresses constructor.
     * @param Context $context
     * @param Registry $registry
     * @param ScopeConfigInterface $config
     * @param TypeListInterface $cacheTypeList
     * @param Escaper $escaper
     * @param ManagerInterface $messageManager
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        ScopeConfigInterface $config,
        TypeListInterface $cacheTypeList,
        Escaper $escaper,
        ManagerInterface $messageManager,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    )
    {
        parent::__construct($context, $registry, $config, $cacheTypeList, $resource, $resourceCollection, $data);
        $this->escaper = $escaper;
        $this->messageManager = $messageManager;
    }

    /**
     * Validate email addresses before save
     *
     * @return $this
     */
    public function beforeSave()
    {
        $restrictedEmails = $this->escaper->escapeHtml($this->getValue());
        $noticeMsgArray = [];
        $restrictedEmailsArray = [];

        if (empty($restrictedEmails)) {
            return parent::beforeSave();
        }

        $dataArray = explode(',', $restrictedEmails);
        foreach ($dataArray as $data) {
            if (filter_var(trim($data), FILTER_VALIDATE_EMAIL)) {
                $restrictedEmailsArray[] = $data;
            } else {
                $restrictedEmailsArray[] = $data;
            }
        }

        $noticeMsg = implode(',', $noticeMsgArray);
        if (!empty($noticeMsgArray)) {
            $this->messageManager->addNoticeMessage(
                __('The following invalid values cannot be saved: %values', ['values' => $noticeMsg])
            );
        }

        $this->setValue(implode(',', $restrictedEmailsArray));
        return parent::beforeSave();
    }
}
