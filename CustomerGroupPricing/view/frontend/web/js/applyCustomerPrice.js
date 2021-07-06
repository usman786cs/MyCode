/**
 *
 * @category : RLTSquare
 * @Package  : RLTSquare_CustomerGroupPricing
 * @Author   : RLTSquare <support@rltsquare.com>
 * @copyright Copyright 2021 © rltsquare.com All right reserved
 * @license https://rltsquare.com/
 */
define([
    "jquery",
    "Magento_Customer/js/customer-data",
    'Magento_Catalog/js/price-utils'

], function ($, customerData, priceUtils) {
    "use strict";

    function main(config, element) {
        var url = config.requestUrl + "/customergrouppricing/product/getcustomerprice";
        var ProductId = config.ProductId;
        var OriginalPrice = config.OriginalPrice;

        $.ajax({
            url: url,
            type: "POST",
            data: {product_id: ProductId},
        }).done(function (data) {
            let finalPrice = 0;

            if (!data.hasOwnProperty("price")) {
                finalPrice = OriginalPrice;
            } else {
                finalPrice = data.price;
            }
            $('#special-price-' + data.product_id).html(config.OriginalSymbol + priceUtils.formatPrice(finalPrice, {decimalSymbol: '.'}));
            return true;
        })
    }

    return main;
});
