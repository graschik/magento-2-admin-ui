define([
    'underscore',
    'mageUtils',
    'uiLayout',
    'Magento_Ui/js/form/element/wysiwyg',
    'ko'
], function (_, utils, layout, Wysiwyg, ko) {
    'use strict';

    return Wysiwyg.extend({
        defaults: {
            links: {
                customValue: '${ $.provider }:${ $.dataScope }',
                value: ''
            },
            listens: {
                value: 'updateValue'
            },
            componentInitialized: false
        },

        initialize: function () {
            this._super();

            this.content = Base64.decode(this.content);
            if (this.customValue()) {
                this.value(Base64.decode(this.customValue()));
            }
            this.componentInitialized = true;

            return this;
        },

        initObservable: function () {

            this._super();
            this.observe('customValue');

            return this;
        },

        updateValue: function () {
            if (!this.componentInitialized) {
                return;
            }
            this.customValue(Base64.encode(this.value()));
        }
    });
});
