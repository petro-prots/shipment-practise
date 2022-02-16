define([
    'uiComponent',
    'Magento_Checkout/js/model/shipping-rates-validator',
    'Magento_Checkout/js/model/shipping-rates-validation-rules',
    '../../model/shipping-rates-validator/zhiguli',
    '../../model/shipping-rates-validation-rules/zhiguli'
], function (
    Component,
    defaultShippingRatesValidator,
    defaultShippingRatesValidationRules,
    zhiguliShippingRatesValidator,
    zhiguliShippingRatesValidationRules
) {
    'use strict';

    defaultShippingRatesValidator.registerValidator('zhiguli', zhiguliShippingRatesValidator);
    defaultShippingRatesValidationRules.registerRules('zhiguli', zhiguliShippingRatesValidationRules);

    return Component;
});
