/*
 * Open Source Music Collection Database (working title)
 *
 * (c) 2015, 2025 Markus Heinz
 * 
 * Licensed under the GPL v3.0
 */

Ext.define('Heinz.cdarchive.ArtistFilter', {
    extend: 'Heinz.cdarchive.ItemFilter',
    labelText: 'Artist:',
    displayField: 'artist_name',
    valueField: 'artist_id',
    store: Ext.create('Heinz.cdarchive.ItemStore', {
        model: Ext.create('Heinz.cdarchive.Artist'),
        proxy: Ext.create('Heinz.cdarchive.JsonProxy', {
            extraParams: {
                cmd: 'artist_list'
            }
        }),
        listeners: {
            'load': function(store, records, successful, eOpts) {
                if (successful) {
                    var staticEntry = [{artist_id: -1, 
                                       artist_name: 'unspecified'}];
                    var data = staticEntry.concat(records);
                    store.setData(data);
                }
            }
        }
    })
});
