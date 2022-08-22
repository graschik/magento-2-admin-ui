define([
    'ko',
    'uiClass'
], function (ko, Class) {
    'use strict';

    return Class.extend({
        /** @inheritdoc */
        initialize: function () {
            this._super()
                .initObservable();

            return this;
        },

        /** @inheritdoc */
        initObservable: function () {
            this.errorMessages = ko.observableArray([]);
            this.successMessages = ko.observableArray([]);
            this.noticeMessages = ko.observableArray([]);
            this.warningMessages = ko.observableArray([]);
            this.messagesConfig = ko.observableArray([
                {type: 'error', messages: this.errorMessages},
                {type: 'success', messages: this.successMessages},
                {type: 'warning', messages: this.warningMessages},
                {type: 'notice', messages: this.noticeMessages},
            ]);

            return this;
        },

        /**
         * Add  message to list.
         * @param {Object} messageObj
         * @param {Object} type
         * @returns {Boolean}
         */
        add: function (messageObj, type) {
            var expr = /([%])\w+/g,
                message;

            if (!messageObj.hasOwnProperty('parameters')) {
                this.clear();
                type.push(messageObj.message);

                return true;
            }
            message = messageObj.message.replace(expr, function (varName) {
                varName = varName.substr(1);

                if (!isNaN(varName)) {
                    varName--;
                }

                if (messageObj.parameters.hasOwnProperty(varName)) {
                    return messageObj.parameters[varName];
                }

                return messageObj.parameters.shift();
            });
            this.clear();
            type.push(message);

            return true;
        },

        /**
         * Add success message.
         *
         * @param {Object} message
         * @return {*|Boolean}
         */
        addSuccessMessage: function (message) {
            return this.add(message, this.successMessages);
        },

        /**
         * Add error message.
         *
         * @param {Object} message
         * @return {*|Boolean}
         */
        addErrorMessage: function (message) {
            return this.add(message, this.errorMessages);
        },

        /**
         * Add notice message.
         *
         * @param {Object} message
         * @return {*|Boolean}
         */
        addNoticeMessage: function (message) {
            return this.add(message, this.noticeMessages);
        },

        /**
         * Add warning message.
         *
         * @param {Object} message
         * @return {*|Boolean}
         */
        addWarningMessage: function (message) {
            return this.add(message, this.warningMessages);
        },

        /**
         * Get error messages.
         *
         * @return {Array}
         */
        getErrorMessages: function () {
            return this.errorMessages;
        },

        /**
         * Get success messages.
         *
         * @return {Array}
         */
        getSuccessMessages: function () {
            return this.successMessages;
        },

        /**
         * Get notice messages.
         *
         * @return {Array}
         */
        getNoticeMessages: function () {
            return this.noticeMessages;
        },

        /**
         * Get warning messages.
         *
         * @return {Array}
         */
        getWarningMessages: function () {
            return this.warningMessages;
        },

        /**
         * Checks if an instance has stored messages.
         *
         * @return {Boolean}
         */
        hasMessages: function () {
            return this.errorMessages().length > 0
                || this.successMessages().length > 0
                || this.noticeMessages().length > 0
                || this.warningMessages().length > 0;
        },

        /**
         * Removes stored messages.
         */
        clear: function () {
            this.errorMessages.removeAll();
            this.successMessages.removeAll();
            this.noticeMessages.removeAll();
            this.warningMessages.removeAll();
        }
    });
});
