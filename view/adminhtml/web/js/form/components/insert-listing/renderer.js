/**
 * @api
 */
define([
    'underscore',
    'uiElement',
    'uiRegistry'
], function (_, Element, registry) {
    'use strict';

    return Element.extend({
        render: function () {
            var
                self = this,
                insertListing,
                componentsWithTheSameNs;

            insertListing = registry.get(self.insertListing);

            componentsWithTheSameNs = registry.filter(function (component) {
                return component.externalListingName == insertListing.ns + '.' + insertListing.ns;
            });
            if (componentsWithTheSameNs.length > 1) {
                insertListing.isRendered = true;
                insertListing.destroyInserted();

                registry.get(this.selectionsProvider, function (selectionsProvider) {
                    if (!selectionsProvider) {
                        return;
                    }

                    if (insertListing.firstLoad) {
                        self.listens[selectionsProvider.name + ':rows'] = 'updateListingFiltersForFirstLoad';
                        self.initLinks();
                    } else {
                        var
                            grid = registry.get(self.grid),
                            selected = [];

                        _.each(grid.recordData(), function (row) {
                            selected.push(row[grid.identificationDRProperty]);
                        });

                        selectionsProvider.selected(selected);
                    }
                });
            }

            insertListing.render();
        },

        updateListingFiltersForFirstLoad: function () {
            var
                insertListing = registry.get(this.insertListing),
                filter = {};


            filter[insertListing.indexField] = {
                'condition_type': insertListing.externalCondition,
                value: []
            };

            insertListing.set('externalFiltersModifier', filter);
            insertListing.needInitialListingUpdate = true;
            insertListing.initialUpdateListing();

            this.listens = [];
            this.initLinks();

            insertListing.firstLoad = false;
        }
    });
});
