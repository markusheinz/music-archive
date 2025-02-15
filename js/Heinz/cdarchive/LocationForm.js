/*
 * Open Source Music Collection Database (working title)
 *
 * (c) 2014, 2025 Markus Heinz
 * 
 * Licensed under the GPL v3.0
 */

Ext.define('Heinz.cdarchive.LocationForm', {
    extend: 'Heinz.cdarchive.ItemForm',
    labelText: 'Location:',
    displayField: 'location_desc',
    valueField: 'location_id',
    store: Ext.create('Heinz.cdarchive.ItemStore', {
        model: Ext.create('Heinz.cdarchive.Location'),
        proxy: Ext.create('Heinz.cdarchive.JsonProxy', {
            extraParams: {
                cmd: 'location_list'
            }
        })
    }),
    addCmd: 'add_location',
    headline: 'Add Location',
});
