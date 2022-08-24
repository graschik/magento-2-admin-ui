define([
    'underscore',
    'mage/translate',
    'Magento_Ui/js/grid/columns/multiselect',
    'uiLayout',
    'Grasch_AdminUi/js/model/messages'
], function (_, $t, Multiselect, layout, Messages) {
    'use strict';

    return Multiselect.extend({
        defaults: {
            message: $t('You can\'t select more than %limit items.'),
            messageType: 'warning'
        },

        /** @inheritdoc */
        initialize: function () {
            this._super();

            if (typeof this.limit == 'undefined') {
                throw new Error('limit option is required.');
            }
            if (typeof this.limit == 'string') {
                this.limit = parseInt(this.limit);
            }

            this.messageContainer = new Messages();
            layout([{
                parent: this.ns + '.' + this.ns,
                name: 'messages',
                component: 'Grasch_AdminUi/js/view/messages',
                config: {
                    messageContainer: this.messageContainer,
                    sortOrder: 0,
                    isAlwaysVisible: true,
                    removeOnClick: false
                }
            }]);

            this.listens['selected'] = 'validateLimit onSelectedChange';
            this.listens['rows'] = 'validateLimit onSelectedChange';
            this.initLinks();

            return this;
        },

        /**
         * Validate limit
         */
        validateLimit: function () {
            if (this.selected().length >= this.limit) {
                var difference = _.difference(this.getIds(), this.selected());
                this.disabled(difference);
                this.showLimitationMessage();
            } else {
                this.disabled([]);
                this.hideLimitationMessage();
            }
        },

        selectPage: function () {
            var difference = _.difference(this.getIds(), this.selected());
            if (difference.length + this.selected().length > this.limit) {
                this.showLimitationMessage();
            } else {
                this._super();
            }
        },

        /**
         * Show limitation message
         */
        showLimitationMessage: function () {
            var
                self = this,
                messageConfig;

            messageConfig = _.find(this.messageContainer.messagesConfig(), function (item) {
                return item.type == self.messageType; //eslint-disable-line eqeqeq
            });

            this.messageContainer.add(
                {
                    message: this.message,
                    parameters: {
                        limit: this.limit
                    }
                },
                messageConfig.messages
            );
        },

        /**
         * Hide limitation message
         */
        hideLimitationMessage: function () {
            this.messageContainer.clear();
        }
    });
});
