<?php
/**
 *
 * @category : RLTSquare
 * @Package  : RLTSquare_CustomerGroupPricing
 * @Author   : RLTSquare <support@rltsquare.com>
 * @copyright Copyright 2021 © rltsquare.com All right reserved
 * @license https://rltsquare.com/
 */

namespace RLTSquare\CustomerGroupPricing\Ui\Component\Listing\Column;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;

/**
 * Class ProductName
 * @package RLTSquare\CustomerGroupPricing\Ui\Component\Listing\Column
 */
class ProductName extends Column
{
    /** Url path */
    const CUSTOMERGROUPPRICING_PRODUCTS_EDIT = 'customergrouppricing/products/edit';

    /** @var UrlBuilder */
    protected $actionUrlBuilder;

    /** @var UrlInterface */
    protected $urlBuilder;

    protected $_coreSession;

    /** @var ProductRepositoryInterface */
    protected $productRepositoryInterface;

    /**
     * ProductName constructor.
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param \Magento\Framework\Session\SessionManagerInterface $coreSession
     * @param ProductRepositoryInterface $productRepositoryInterface
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        \Magento\Framework\Session\SessionManagerInterface $coreSession,
        ProductRepositoryInterface $productRepositoryInterface,
        array $components = [],
        array $data = []
    )
    {
        $this->urlBuilder = $urlBuilder;
        $this->_coreSession = $coreSession;
        $this->productRepositoryInterface = $productRepositoryInterface;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * @param array $dataSource
     * @return array
     * @throws NoSuchEntityException
     */
    public function prepareDataSource(array $dataSource): array
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $name = $this->getData('name');
                if (isset($item['entity_id'])) {
                    try {
                        $product = $this->productRepositoryInterface->getById($item['entity_id']);
                        $item['product_name'] = $product->getName();
                    } catch (NoSuchEntityException $noSuchEntityException) {
                        throw new NoSuchEntityException(
                            __("Product with ID %1 does not exist.", $item['entity_id'])
                        );
                    }
                }
            }
        }
        return $dataSource;
    }
}
