/*
 * Open Source Music Collection Database (working title)
 *
 * (c) 2015 Markus Heinz
 * 
 * Licensed under the GPL v3.0
 */

Ext.define('Heinz.cdarchive.LocationFilter', {
    extend: 'Heinz.cdarchive.ItemFilter',
    labelText: 'Location:',
    labelMargin: '0 10 0 0',
    displayField: 'location_desc',
    valueField: 'location_id',
    store: Ext.create('Heinz.cdarchive.ItemStore', {
        model: Ext.create('Heinz.cdarchive.Location'),
        proxy: Ext.create('Heinz.cdarchive.JsonProxy', {
            extraParams: {
                cmd: 'location_list'
            }
        }),
        listeners: {
            'load': function(store, records, successful, eOpts) {
                if (successful) {
                    var staticEntry = [{location_id: -1, 
                                       location_desc: 'unspecified'}];
                    var data = staticEntry.concat(records);
                    store.setData(data);
                }
            }
        }
    })
});
