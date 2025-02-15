/*
 * Open Source Music Collection Database (working title)
 *
 * (c) 2015, 2025 Markus Heinz
 * 
 * Licensed under the GPL v3.0
 */

Ext.define('Heinz.cdarchive.GenreFilter', {
    extend: 'Heinz.cdarchive.ItemFilter',
    labelText: 'Genre:',
    displayField: 'genre',
    valueField: 'genre_id',
    store: Ext.create('Heinz.cdarchive.ItemStore', {
        model: Ext.create('Heinz.cdarchive.Genre'),
        proxy: Ext.create('Heinz.cdarchive.JsonProxy', {
            extraParams: {
                cmd: 'genre_list'
            }
        }),
        listeners: {
            'load': function(store, records, successful, eOpts) {
                if (successful) {
                    var staticEntry = [{genre_id: -1, 
                                       genre: 'unspecified'}];
                    var data = staticEntry.concat(records);
                    store.setData(data);
                }
            }
        }
    })
});
