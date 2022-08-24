define([
    'ko',
    'jquery',
    'uiComponent',
    'uiLayout',
    'Grasch_AdminUi/js/model/messages',
    'mage/translate'
], function (ko, $, Component, layout, Messages, $t) {
    'use strict';

    return Component.extend({
        defaults: {
            listens: {
                isHidden: 'onHiddenChange',
                '${ $.provider }:${ $.customScope ? $.customScope + "." : ""}data.validate': 'validate',
                '${ $.name + "." + $.index }:recordData': 'validate'
            },
            modules: {
                grid: '${ $.name + "." + $.index }'
            },
            minMessage: $t('You must select at least %limit items.'),
            maxMessage: $t('You can\'t select more than %limit items.')
        },

        /**
         * @inheritdoc
         */
        initialize: function () {
            this._super();

            this.messageContainer = new Messages();
            layout([{
                parent: this.name + '.button_set',
                name: 'messages',
                component: 'Grasch_AdminUi/js/view/messages',
                config: {
                    displayArea: 'messages',
                    messageContainer: this.messageContainer,
                    sortOrder: 0,
                    isAlwaysVisible: true,
                    removeOnClick: false
                }
            }]);

            return this;
        },

        /**
         * @return {Object}
         */
        validate: function () {
            if (!this.limit) {
                return true;
            }

            var isValid = true;

            if (!this.validateMinLimit()) {
                this.messageContainer.addWarningMessage({
                    message: this.minMessage,
                    parameters: {
                        limit: this.limit.min
                    }
                });
                isValid = false;
            } else if (!this.validateMaxLimit()) {
                this.messageContainer.addWarningMessage({
                    message: this.maxMessage,
                    parameters: {
                        limit: this.limit.max
                    }
                });
                isValid = false;
            }

            if (this.source && !isValid) {
                this.source.set('params.invalid', true);
            }
            if (isValid) {
                this.messageContainer.clear();
            }

            return {
                valid: isValid,
                target: this
            };
        },

        /**
         * @return {Boolean}
         */
        validateMaxLimit: function () {
            if (!this.limit || !this.limit.max) {
                return true;
            }

            return this.grid().recordData().length <= this.limit.max;
        },

        /**
         * @return {Boolean}
         */
        validateMinLimit: function () {
            if (!this.limit || !this.limit.min) {
                return true;
            }

            return this.grid().recordData().length >= this.limit.min;
        }
    });
});
