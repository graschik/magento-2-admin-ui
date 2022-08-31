define([
    'underscore',
    'mageUtils',
    'uiLayout',
    'Magento_Ui/js/form/element/abstract',
    'ko',
    'jquery'
], function (_, utils, layout, Abstract, ko, $) {
    'use strict';

    return Abstract.extend({
        defaults: {
            links: {
                value: '${ $.provider }:${ $.dataScope }'
            },
            listens: {
                value: 'updateCustomValue'
            },
            valueInitialized: false
        },

        /**
         * Initializes observable properties of instance
         *
         * @returns {Abstract} Chainable.
         */
        initObservable: function () {

            this._super();
            this.observe('customValue');

            return this;
        },

        getInitialValue: function () {
            this.valueInitialized = true;

            return this.value();
        },

        setInitialValue: function () {
            this._super();

            this.updateCustomValue();
            return this;
        },

        updateCustomValue: function () {
            if (!this.valueInitialized) {
                return;
            }

            var
                value = this.value(),
                customValue;

            if (typeof value == 'object') {
                customValue = JSON.stringify($.extend({}, value));
            } else {
                customValue = value;
            }

            this.customValue('encodedComponentsData|' + Base64.encode(customValue));
        }
    });
});
