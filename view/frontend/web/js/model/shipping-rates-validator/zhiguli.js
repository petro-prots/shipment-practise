define([
    'jquery',
    'mageUtils',
    '../shipping-rates-validation-rules/zhiguli',
    'mage/translate'
], function ($, utils, validationRules, $t) {
    'use strict';

    return {
        validationErrors: [],

        /**
         * @param {Object} address
         * @return {Boolean}
         */
        validate: function (address) {
            this.validationErrors = [];

            return true;
        }
    };
});
