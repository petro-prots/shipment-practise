define([
    'uiComponent',
    'Magento_Checkout/js/model/shipping-rates-validator',
    'Magento_Checkout/js/model/shipping-rates-validation-rules',
    '../../model/shipping-rates-validator/np',
    '../../model/shipping-rates-validation-rules/np'
], function (
    Component,
    defaultShippingRatesValidator,
    defaultShippingRatesValidationRules,
    npShippingRatesValidator,
    npShippingRatesValidationRules
) {
    'use strict';

    defaultShippingRatesValidator.registerValidator('np', npShippingRatesValidator);
    defaultShippingRatesValidationRules.registerRules('np', npShippingRatesValidationRules);

    return Component;
});
