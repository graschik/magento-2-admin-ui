/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * @api
 */
define([
    'underscore',
    'uiElement',
    'uiRegistry',
    'ko'
], function (_, Element, registry, ko) {
    'use strict';

    return Element.extend({
        prepareSelections: function () {
            var self = this;

            registry.get(this.selectionsProvider, function (selectionsProvider) {
                if (!selectionsProvider) {
                    return;
                }

                var
                    grid = registry.get(self.gridProvider),
                    insertListing = registry.get(self.insertListingProvider),
                    selected = [];

                _.each(grid.recordData(), function (row) {
                   selected.push(row[grid.identificationDRProperty]);
                });

                selectionsProvider.selected(selected);
                insertListing.save();
            });
        }
    });
});
