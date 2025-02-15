/*
 * Open Source Music Collection Database (working title)
 *
 * (c) 2015, 2025 Markus Heinz
 * 
 * Licensed under the GPL v3.0
 */

Ext.define('Heinz.cdarchive.YearFilter', {
    extend: 'Heinz.cdarchive.ItemFilter',
    labelText: 'Year:',
    displayField: 'album_year',
    valueField: 'album_year',
    store: Ext.create('Heinz.cdarchive.ItemStore', {
        model: Ext.create('Heinz.cdarchive.Year'),
        proxy: Ext.create('Heinz.cdarchive.JsonProxy', {
            extraParams: {
                cmd: 'year_list'
            }
        }),
        listeners: {
            'load': function(store, records, successful, eOpts) {
                if (successful) {
                    var staticEntry = [{album_year: -1}];
                    var data = staticEntry.concat(records);
                    store.setData(data);
                }
            }
        }
    })
});
