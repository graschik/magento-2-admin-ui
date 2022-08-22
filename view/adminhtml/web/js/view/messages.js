define([
    'ko',
    'jquery',
    'uiComponent',
    'Grasch_AdminUi/js/model/messageList',
    'jquery-ui-modules/effect-blind'
], function (ko, $, Component, globalMessages) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Grasch_AdminUi/messages',
            selector: '[data-role=messages]',
            isHidden: false,
            hideTimeout: 5000,
            hideSpeed: 500,
            isAlwaysVisible: false,
            removeOnClick: true,
            listens: {
                isHidden: 'onHiddenChange'
            }
        },

        /** @inheritdoc */
        initialize: function (config, messageContainer) {
            this._super()
                .initObservable();

            this.messageContainer = messageContainer || config.messageContainer || globalMessages;

            return this;
        },

        /** @inheritdoc */
        initObservable: function () {
            this._super()
                .observe('isHidden');

            return this;
        },

        /**
         * Checks visibility.
         *
         * @return {Boolean}
         */
        isVisible: function () {
            if (this.isAlwaysVisible) {
                return true;
            }

            return this.isHidden(this.messageContainer.hasMessages());
        },

        /**
         * Remove all messages.
         */
        removeAll: function () {
            if (this.removeOnClick) {
                this.messageContainer.clear();
            }
        },

        /**
         * @param {Boolean} isHidden
         */
        onHiddenChange: function (isHidden) {
            // Hide message block if needed
            if (isHidden) {
                setTimeout(function () {
                    $(this.selector).hide('blind', {}, this.hideSpeed);
                }.bind(this), this.hideTimeout);
            }
        },
    });
});
